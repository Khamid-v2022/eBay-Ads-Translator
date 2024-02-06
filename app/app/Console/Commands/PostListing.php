<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;
use App\Models\SourceMarketPlace;
use App\Models\Store;
use App\Models\MarketPlace;
use App\Models\MarketPlaceDetail;
use App\Models\Item;
use Google\Cloud\Translate\V2\TranslateClient;
use GuzzleHttp\Client;

class PostListing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post-item:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $translate;

    public function __construct()
    {
        parent::__construct();

        $this->translate = new TranslateClient([
            'keyFilePath' => storage_path('keys/valid-delight-406517-0dd5e4308e54.json'),
        ]);
    }
    public function handle()
    {
        $appId = 'AspetTad-AdTransl-PRD-1fcac1c68-5bf3c305'; // Replace with your eBay App ID
        $devId = '86bb8d82-3676-41fa-9753-a768f440589d'; // Replace with your eBay Dev ID
        $certId = 'PRD-fcac1c68ce5d-43d1-4689-af0b-fee6'; // Replace with your eBay Cert ID
        $apiUrl = 'https://api.ebay.com/ws/api.dll';
        $data_sources = SourceMarketplace::with('target_marketplaces', 'store')->get();
        
        $result = [];

        $currentTime = time();
        Log::info(date('Y-m-d h:i A', $currentTime));
        $time_diff = 43200;
        $time_diff = '-' . $time_diff . ' second';
        Log::info(' Time DiFF: ' . $time_diff);
        $startTime = strtotime($time_diff, $currentTime);

        foreach ($data_sources as $sourceData) {
            $target_marketplaces = [];
            $source_marketplace_obj  = MarketPlace::where('site_id',$sourceData->site_id)->first();
            $source_site_code_3chars = $source_marketplace_obj->site_code;
            $source_site_code_2chars = substr($source_site_code_3chars,0,2);


            foreach ($sourceData->target_marketplaces as $target_data) {
                $target_site_id = $target_data->site_id;
                $target_marketplaces[] = $target_site_id;
            }
            $result[] = [
                'source_store' => $sourceData->store_id,
                'source_country' => $sourceData->site_id,
                'source_token' => $sourceData->store->access_token,
                'target_marketplaces' => $target_marketplaces
            ];
        }
        foreach ($result as $data) {
            $token = $data['source_token'];

            $startTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $startTime);
            $endTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $currentTime);
            $headers = [
                'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
                'X-EBAY-API-SITEID:' . $data['source_country'],
                'X-EBAY-API-CALL-NAME: GetSellerList',
                'X-EBAY-API-IAF-TOKEN:' . $token,
                'Content-Type: text/xml;charset=utf-8',
            ];
            $requestXmlBody = <<<XML
            <?xml version="1.0" encoding="utf-8"?>
            <GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                <RequesterCredentials>
                    <eBayAuthToken>$token</eBayAuthToken>
                </RequesterCredentials>
                <Pagination>
                    <EntriesPerPage>10</EntriesPerPage>
                    <PageNumber>1</PageNumber>
                </Pagination>
                <StartTimeFrom>{$startTimeISO8601}</StartTimeFrom>
                <StartTimeTo>{$endTimeISO8601}</StartTimeTo>
                <ActiveList>
                    <Include>true</Include>
                </ActiveList>
                <PictureDetails>
                    <PhotoDisplay>PicturePack</PhotoDisplay>
                    <IncludeStockPhotoURL>true</IncludeStockPhotoURL>
                    <IncludeGalleryURL>true</IncludeGalleryURL>
                </PictureDetails>
                <DetailLevel>ReturnAll</DetailLevel>
            </GetSellerListRequest>
            XML;
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                Log::error('cURL error: ' . curl_error($ch));
            }
            curl_close($ch);
            $res = simplexml_load_string($response);

           
            $source_country_code = '';
            $activeListings = [];
            $translatedListings = [];

            if (empty($res->ItemArray->Item)) {
                Log::info($res->ItemArray->Item);
                Log::info('ITEM IS NULL on Line # ' . __LINE__);
                dd('');
            }

            foreach ($res->ItemArray->Item as $item) {
                $listingStatus = (string) $item->SellingStatus->ListingStatus;
               
                if ($listingStatus === 'Active') {

                    if($source_site_code_2chars != $item->Country){
                        Log::info('Country did not match');
                        Log::info($source_site_code_2chars . ' - '. $item->Country);
                        continue;
                    }
                    

                    $itemId = $item->ItemID;
                    $headers = [
                        'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
                        'X-EBAY-API-SITEID: ' . $data['source_country'],
                        'X-EBAY-API-CALL-NAME: GetItem',
                        'X-EBAY-API-IAF-TOKEN: ' . $token,
                        'Content-Type: text/xml;charset=utf-8',
                    ];
                    $requestXmlBody = <<<XML
                    <?xml version="1.0" encoding="utf-8"?>
                    <GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                        <RequesterCredentials>
                            <eBayAuthToken>{$token}</eBayAuthToken>
                        </RequesterCredentials>
                        <ItemID>{$itemId}</ItemID>
                        <IncludeItemSpecifics>true</IncludeItemSpecifics>
                    </GetItemRequest>
                    XML;
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    if (curl_errno($ch)) {
                        Log::error('cURL error: ' . curl_error($ch));
                    }
                    curl_close($ch);

                    $resp = simplexml_load_string($response);

                    $current_price = (float)$item->SellingStatus->CurrentPrice;


                    $source_country_code = (string)$item->Country;

                    $activeListings[] = [
                        "title" => (string)$item->Title,
                        "description" => (string)$item->Description,
                        "listingType" => (string)$item->ListingType,
                        "quantity" => (string)$item->Quantity,
                        "startPrice" => $current_price,
                        "conditionID" => (string)$item->ConditionID,
                        "ConditionDisplayName" => (string)$item->ConditionDisplayName,
                        "category_id" => (string)$item->PrimaryCategory->CategoryID,
                        "CategoryName" => (string)$item->PrimaryCategory->CategoryName,
                        "country" => (string)$item->Country,
                        "currency" => (string)$item->Currency,
                        "ReturnsAcceptedOption" => (string)$item->ReturnPolicy->ReturnsAcceptedOption,
                        "ReturnsAccepted" => (string)$item->ReturnPolicy->ReturnsAccepted,
                        "PictureURL" => $item->PictureDetails->PictureURL,
                        "token" => $token,
                        "ItemSpecifics" => $resp->Item->ItemSpecifics
                    ];

                    Log::info('Active Listing Count : ' . count($activeListings));
                }
            }
            if (empty($activeListings)) {
                Log::info("No Active listing found in current run!");
                return 1;
            }
            // dump($activeListings);
            foreach ($data['target_marketplaces'] as $target_marketplace) {
                $target_market = MarketPlace::where('site_id', '=', $target_marketplace)->get();
                foreach ($target_market as $markets) {
                    $site_code_two_alph = substr($markets->site_code, 0, 2);

                    $translate_lang_code = $site_code_two_alph == 'US' ? 'EN' : $site_code_two_alph;

                    $translatedListing = [];
                    foreach ($activeListings as $_key => $listing) {
                        $translatedListing[$_key] = [
                            "title" => $this->translateText($listing['title'], $translate_lang_code),
                            "description" => $listing['description'],
                            "listingType" => $listing['listingType'],
                            "quantity" => $listing['quantity'],
                            "startPrice" => $listing['startPrice'],
                            "conditionID" => $listing['conditionID'],
                            "ConditionDisplayName" => $listing['ConditionDisplayName'],
                            "category_id" => $listing['category_id'],
                            "CategoryName" => $listing['CategoryName'],
                            "country" => $listing['country'],
                            "currency" => $listing['currency'],
                            "ReturnsAcceptedOption" => $listing['ReturnsAcceptedOption'],
                            "ReturnsAccepted" =>$listing['ReturnsAccepted'],
                            "PictureURL" => $listing['PictureURL'],
                            "token" => $token
                        ];
                        $product_type_translations = [
                            'ES' => 'Tipo',
                            'IT' => 'Tipo',
                            'FR' => 'Type',
                            'DE' => 'Produktart',
                            'EN' => 'Type',
                        ];
                        $item_specifics_arr = [];
                        if (!empty($listing['ItemSpecifics']->NameValueList)) {
                            foreach ($listing['ItemSpecifics']->NameValueList as $k => $spec) {
                                if (
                                    isset($product_type_translations[$source_country_code])
                                    && $product_type_translations[$source_country_code] == (string)$spec->Name
                                ) {

                                    $translatedName = $product_type_translations[$translate_lang_code];
                                } else {
                                    $translatedName = $this->translateText((string)$spec->Name, $translate_lang_code);
                                }
                                $translatedValue = $this->translateText((string)$spec->Value, $translate_lang_code);
                                $item_specifics_arr[] = [
                                    'Name' => $translatedName,
                                    'Value' => $translatedValue,
                                ];
                            }
                        }
                        $translatedListing[$_key]['ItemSpecifics'] = $item_specifics_arr;
                    }
                    $translatedListings[$target_marketplace] = $translatedListing;
                }
            }
            Log::info('RUN CREATE!');
            Log::info('Translated Total: ' . count($translatedListings));
            $targets_markeplaces = $data['target_marketplaces'];
            if (!empty($translatedListings) && !empty($targets_markeplaces)) {
                $this->create($translatedListings, $targets_markeplaces);
            }
        }
    }


    public function create($translatedListings, $targets_markeplaces)
    {

        $apiUrl = 'https://api.ebay.com/ws/api.dll';
        $matchingCategoryId = null;
        $matchedMarketplace = null;
        $foundMatch = false;
        $created_count = 0;

        foreach ($targets_markeplaces as $target_site_id) {
            $site_detail = MarketPlace::where('site_id', '=', $target_site_id)->first()->toArray();
            if (!isset($translatedListings[$target_site_id])) {
                Log::info('No translated listing for Target site id : ' . $target_site_id);
                continue;
            }
            $items = $translatedListings[$target_site_id];
            foreach($items as $item){

                if (empty($item)) {
                    continue;
                }

                $token = $item['token'];
                $title = $item['title'];
                $trimmedTitle = substr($title, 0, 80);
                $description = $item['description'];
                $listingType = $item['listingType'];
                $quantity = $item['quantity'];
                $startPrice = $item['startPrice'];

                $conditionID = $item['conditionID'];
                $conditionDisplayName = $item['ConditionDisplayName'];

                $catResp = $this->getAllCategory($target_site_id, $token, $apiUrl);
                $allList = $catResp->CategoryArray->Category;

                //category ID 123422
                $category_id = $item['category_id'];
                $CategoryName = $item['CategoryName'];
                if (!empty($allList)) {
                    foreach ($allList as $category) {

                        if ((string)$category->CategoryID === $category_id) {
                            if ($category->LeafCategory) {
                                $matchingCategoryId = (string)$category->CategoryID;
                                $matchedMarketplace = $target_site_id;
                                $foundMatch = true;
                            } else {
                                if ($target_site_id == 0) {
                                    $category_id = 35683;
                                } else {
                                    $category_id = 182161;
                                }

                                $foundMatch = false;
                            }
                        }
                    }

                    if ($foundMatch) {
                        Log::info("Category ID $category_id matched for $matchedMarketplace. Matched Category ID: $matchingCategoryId" . "\n");
                        $category_id = $matchingCategoryId;
                        $foundMatch = false;
                    } else {
                        Log::error("Category ID $category_id not matched for $target_site_id." . "\n");
                        if ($target_site_id == 0) {
                            $category_id = 35683;
                        } else {
                            $category_id = 182161;
                        }
                    }
                }


                $country = $item['country'];
                $ReturnsAccepted = htmlspecialchars_decode($item['ReturnsAccepted']);
                $ReturnsAcceptedOption = $item['ReturnsAcceptedOption'];
                $pictures = $item['PictureURL'];

                $target_site_currency = $site_detail['site_currency'];
                $target_site_code = $site_detail['site_code'];
                $site_code_two_alph = substr($target_site_code, 0, 2);

                $markets_details = MarketPlaceDetail::where('site_id', '=', $target_site_id)->get();
                foreach ($markets_details as $markets_detail) {

                    $shipping_service = $markets_detail->shipping_service;
                    $shipping_service_cost = $markets_detail->shipping_service_cost;
                    $free_shipping = $markets_detail->free_shipping;
                    $shipping_type = $markets_detail->shipping_type;
                    $dispatch_time_max = $markets_detail->dispatch_time_max;
                    $location = $markets_detail->location;
                    if ($free_shipping == 1) {
                        $free_shipping = "true";
                    } else {
                        $free_shipping = "false";
                    }

                    if ($target_site_currency == "EUR") {
                        $convertion_rate = 1;
                    } else if ($target_site_currency == "USD") {
                        $convertion_rate = 1.1;
                    } else {
                        continue;
                    }
                    $final_price = $startPrice * $convertion_rate;

                    // dump($resp);
                    // continue;
                    $headers_additem = [
                        'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
                        'X-EBAY-API-SITEID:' . $target_site_id,
                        'X-EBAY-API-CALL-NAME:AddItem',
                        'X-EBAY-API-IAF-TOKEN:' . $token,
                    ];

                    $requestxml = <<<XML
                    <?xml version="1.0" encoding="utf-8"?>
                    <AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                        <RequesterCredentials>
                            <eBayAuthToken>$token</eBayAuthToken>
                        </RequesterCredentials>
                        <ErrorLanguage>en_US</ErrorLanguage>
                        <WarningLevel>High</WarningLevel>
                        <Item>
                            <Country>$site_code_two_alph</Country>
                            <Currency>$target_site_currency</Currency>
                            <ListingType>FixedPriceItem</ListingType>
                            <Location>$location</Location>
                            <PrimaryCategory>
                                <CategoryID>$category_id</CategoryID>
                            </PrimaryCategory>
                            <Title>$trimmedTitle</Title>
                            <Description><![CDATA[$description]]></Description>
                            <StartPrice>$final_price</StartPrice>
                            <Quantity>$quantity</Quantity>
                            <ConditionID>$conditionID</ConditionID>
                            <ReturnPolicy>
                                <ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
                                <RefundOption>MoneyBack</RefundOption>
                                <ReturnsWithinOption>Days_30</ReturnsWithinOption>
                                <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
                            </ReturnPolicy>
                            <ShippingDetails>
                                <ShippingType>$shipping_type</ShippingType>
                                <ShippingServiceOptions>
                                    <ShippingServicePriority>1</ShippingServicePriority>
                                    <ShippingService>$shipping_service</ShippingService>
                                    <ShippingServiceCost>$shipping_service_cost</ShippingServiceCost>
                                    <FreeShipping>$free_shipping</FreeShipping>
                                </ShippingServiceOptions>
                                <InternationalShippingDiscountProfileID>0</InternationalShippingDiscountProfileID>
                            </ShippingDetails>
                            <ListingDuration>Days_7</ListingDuration>
                            <ShipToLocations>$site_code_two_alph</ShipToLocations>
                            <DispatchTimeMax>3</DispatchTimeMax>
                            <PictureDetails>
                    XML;

                    foreach ($pictures as $url) {
                        $requestxml .= "<PictureURL>$url</PictureURL>";
                    }

                    $requestxml .= <<<XML
                            </PictureDetails>
                    XML;

                    if (!empty($item['ItemSpecifics'])) {
                        $requestxml .= <<<XML
                            <ItemSpecifics>
                        XML;

                        foreach ($item['ItemSpecifics'] as $spec) {
                            $name  = (string)$spec['Name'];
                            $value = (string)$spec['Value'];
                            $requestxml .= <<<XML
                                <NameValueList>
                                    <Name>$name</Name>
                                    <Value>$value</Value>
                                </NameValueList>
                                XML;
                        }

                        $requestxml .= <<<XML
                            </ItemSpecifics>
                        XML;
                    }

                    $requestxml .= <<<XML
                        </Item>
                    </AddItemRequest>
                    XML;

                    // dump($item['ItemSpecifics']);
                    // dump($requestxml);
                    // continue;
                    $curl = curl_init($apiUrl);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers_additem);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestxml);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response_data = curl_exec($curl);

                    if ($response_data === false) {
                        Log::error('cURL error: ' . curl_error($ch));
                    } else {
                        $xml_response = simplexml_load_string($response_data);
                        if ($xml_response->Ack == 'Success') {
                            Log::info("Items listed successfully");
                            $created_count++;
                        } else {
                            Log::error(print_r($xml_response, true));
                        }
                    }
                    curl_close($curl);
                }
                Log::info('Listing Created Count :' . $created_count);
            }
        }
    }
    function getAllCategory($target_site_id, $token, $apiUrl)
    {
        $headers_additem = [
            'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
            'X-EBAY-API-SITEID:' . $target_site_id,
            'X-EBAY-API-CALL-NAME:GetCategories',
            'X-EBAY-API-IAF-TOKEN:' . $token,
        ];

        $requestxml = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
                <eBayAuthToken>$token</eBayAuthToken>
            </RequesterCredentials>
            <CategorySiteID>$target_site_id</CategorySiteID> <!-- 0 for US site -->
            <DetailLevel>ReturnAll</DetailLevel>
            
        </GetCategoriesRequest>
        XML;

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers_additem);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $requestxml);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response_data = curl_exec($curl);
        curl_close($curl);
        return $resp = simplexml_load_string($response_data);
    }
    private function translateText($text, $targetLanguage)
    {
        if (empty($text)) {
            return $text;
        }

        if (is_array($text)) {
            return array_map(function ($item) use ($targetLanguage) {
                return $this->translateText($item, $targetLanguage);
            }, $text);
        }

        $result = $this->translate->translate($text, [
            'target' => $targetLanguage,
        ]);

        return $result['text'];
    }

    function translateArray($text, $targetLanguage)
    {
        if (empty($text)) {
            return $text;
        }
        $client = new Client();
        try {
            $response = $client->request('POST', 'https://google-translate1.p.rapidapi.com/language/translate/v2', [
                'form_params' => [
                    'q' => $text,
                    'target' => $targetLanguage
                ],
                'headers' => [
                    'Accept-Encoding' => 'application/gzip',
                    'X-RapidAPI-Host' => 'google-translate1.p.rapidapi.com',
                    'X-RapidAPI-Key' => 'de4eaa36d6msh5ab53fba6c8b3e3p118b8bjsn2ec1fd682c0b',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);
            $result = json_decode($response->getBody(), true);
            return $result['data']['translations'][0]['translatedText'];
        } catch (\Exception $e) {
            return $text;
        }
    }
}
