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
use DateTime;
use DateTimeZone;

class PostHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'post-history:run';

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
        $days = 5;
        $seconds_in_days = $days * 24 * 60 * 60;
        $time_diff = '-' . $seconds_in_days . ' second';
        $currentTime = time();
        Log::info(' Current Time: ' . date('Y-m-d h:i A'));
        $startTime = strtotime($time_diff, $currentTime);

        $def_source_country_site_code = 186; // 186 Spain

        $total_active_listings = 0;

        foreach ($data_sources as $sourceData) {
            $target_marketplaces = [];
            $source_marketplace_obj  = MarketPlace::where('site_id', $sourceData->site_id)->first();
            $source_site_code_3chars = $source_marketplace_obj->site_code;
            $source_site_code_2chars = substr($source_site_code_3chars, 0, 2);

            foreach ($sourceData->target_marketplaces as $target_data) {
                $target_site_id = $target_data->site_id;
                $target_marketplaces[] = $target_site_id;
            }

            $result[] = [
                'source_store' => $sourceData->store_id,
                'source_country' => $sourceData->site_id,
                'source_country' => $sourceData->site_id,
                'source_token' => $sourceData->store->access_token,
                'target_marketplaces' => $target_marketplaces
            ];
        }

        foreach ($result as $data) {

            $token   = $data['source_token'];
            // $token = "v^1.1#i^1#I^3#p^3#f^0#r^1#t^Ul4xMF80OjE1RUU3RjNGRTE0NzRDRDU5RkVEQjlCRjgzRjhENjM3XzNfMSNFXjI2MA==";
            $site_id = $data['source_country'];

            $getTotalListing = $this->getTotalListings($apiUrl, $token, $site_id);
          
          	// dd($getTotalListing);

            $batchSize = 10;
            $totalIterations = ceil($getTotalListing / $batchSize);
            $start_page_number   = 1;

            Log::info('Page #.' . $start_page_number);
            for ($pageNumber = $start_page_number; $pageNumber <= $totalIterations; $pageNumber++) {
                Log::info('Page #.' . $pageNumber);

                // if($pageNumber >= 80){
                //     Log::info('Total Active Listing Count : '. $total_active_listings);
                //     Log::info('Page #.'. $pageNumber);
                //     Log::info('Reached!');
                //     die;
                // }

                // $startTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $startTime);
                // $endTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $currentTime);

                $headers = [
                    'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
                    'X-EBAY-API-SITEID: ' . $def_source_country_site_code,
                    'X-EBAY-API-CALL-NAME: GetMyeBaySelling',
                    'X-EBAY-API-IAF-TOKEN:' . $token,
                    'Content-Type: text/xml;charset=utf-8',
                ];
                $requestXmlBody = <<<XML
                <?xml version="1.0" encoding="utf-8"?>
                <GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                <RequesterCredentials>
                    <eBayAuthToken>$token</eBayAuthToken>
                </RequesterCredentials>
                <ActiveList>
                    <Sort>TimeLeft</Sort>
                    <Pagination>
                        <EntriesPerPage>{$batchSize}</EntriesPerPage>
                        <PageNumber>{$pageNumber}</PageNumber>
                    </Pagination>
                </ActiveList>
                <DetailLevel>ReturnAll</DetailLevel>
                </GetMyeBaySellingRequest>
                XML;
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'cURL error: ' . curl_error($ch);
                }
                curl_close($ch);
                $res = simplexml_load_string($response);
                //$entries = $res->ActiveList->ItemArray->Item; // Assuming this is how items are represented in the response
                // $totalEntries = count($entries);
                // Log::info($totalEntries);
                // continue;
                $source_country_code = '';
                $activeListings = [];
                $translatedListings = [];
                $targetItemIndex = 7;
                $curent_count = 0;
                //$loopExecuted = false;
                // if (isset($res->ActiveList->ItemArray->Item[$targetItemIndex])) {
                //     $item = $res->ActiveList->ItemArray->Item[$targetItemIndex];

                if (empty($res->ActiveList->ItemArray->Item)) {
                    Log::info($res->ActiveList);
                    Log::info('ITEM IS NULL on Line # ' . __LINE__);
                    dd('');
                }

                foreach ($res->ActiveList->ItemArray->Item as $key => $item) {
                    $curent_count++;


                    // Log::info('Current Key:' . $curent_count);
                    // Log::info('Target Key:' . $targetItemIndex);

                    // if($curent_count < $targetItemIndex && $pageNumber == $start_page_number){
                    //     Log::info('Skip number');
                    //     continue;
                    // }

                    // if ($loopExecuted) {
                    //     break;
                    // }

                    $itemId = $item->ItemID;
                    $headers = [
                        'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
                        'X-EBAY-API-SITEID: ' . $def_source_country_site_code,
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
                                <DetailLevel>ReturnAll</DetailLevel>
                            </GetItemRequest>
                            XML;
                    $ch = curl_init($apiUrl);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'cURL error: ' . curl_error($ch);
                    }
                    curl_close($ch);

                    $resp = simplexml_load_string($response);

                    $current_price = (float)$item->SellingStatus->CurrentPrice;

                    if ($source_site_code_2chars != $resp->Item->Country) {
                        Log::info('Country did not match');
                        Log::info($source_site_code_2chars . ' - ' . $resp->Item->Country);
                        continue;
                    }

                    $activeListings[] = [
                        "title" => (string)$resp->Item->Title,
                        "description" => (string)$resp->Item->Description,
                        "listingType" => (string)$resp->Item->ListingType,
                        "quantity" => (string)$resp->Item->Quantity,
                        "startPrice" => $current_price,
                        "conditionID" => (string)$resp->Item->ConditionID,
                        "ConditionDisplayName" => (string)$resp->Item->ConditionDisplayName,
                        "category_id" => (string)$resp->Item->PrimaryCategory->CategoryID,
                        "CategoryName" => (string)$resp->Item->PrimaryCategory->CategoryName,
                        "country" => (string)$resp->Item->Country,
                        "currency" => (string)$resp->Item->Currency,
                        "ReturnsAcceptedOption" => (string)$resp->Item->ReturnPolicy->ReturnsAcceptedOption,
                        "ReturnsAccepted" => (string)$resp->Item->ReturnPolicy->ReturnsAccepted,
                        "PictureURL" => $resp->Item->PictureDetails->PictureURL,
                        "token" => $token,
                        "ItemSpecifics" => $resp->Item->ItemSpecifics
                    ];
                    //}   
                    $targetItemIndex++;
                    // $loopExecuted = true;

                    $total_active_listings++;
                    Log::info('Active Listing Count : ' . count($activeListings));
                    Log::info('Total Active Listing Count : ' . $total_active_listings);
                }

                if (empty($activeListings)) {
                    Log::info("No Active listing found in current run!");
                    return 1;
                }
                // Log::info($activeListings);
                //}

                foreach ($data['target_marketplaces'] as $target_marketplace) {
                    $target_market = MarketPlace::where('site_id', '=', $target_marketplace)->get();
                    foreach ($target_market as $markets) {
                        $site_code_two_alph = substr($markets->site_code, 0, 2);

                        $translate_lang_code = $site_code_two_alph == 'US' ? 'EN' : $site_code_two_alph;

                        // for country specific listing
                        // if ($translate_lang_code != 'EN') {
                        //     continue;
                        // }

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
                                "ReturnsAccepted" => $listing['ReturnsAccepted'],
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
                Log::info('Total ACT Count : ' . $total_active_listings);
                Log::info('Translated Total: ' . count($translatedListings));
                Log::info($translatedListings);
                // die;

                $targets_markeplaces = $data['target_marketplaces'];
                if (!empty($translatedListings) && !empty($targets_markeplaces)) {
                    $this->create($translatedListings, $targets_markeplaces);
                }
            }
            //forloop
        }
    }

    function getTotalListings($apiUrl, $token, $site_id)
    {
        $requestXmlBody = <<<XML
            <?xml version="1.0" encoding="utf-8"?>
            <GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                <RequesterCredentials>
                    <eBayAuthToken>$token</eBayAuthToken>
                </RequesterCredentials>
                <ActiveList>
                    <Include>true</Include>
                </ActiveList>
                <DetailLevel>ReturnAll</DetailLevel>
            </GetMyeBaySellingRequest>
        XML;

        $headers = [
            'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
            'X-EBAY-API-SITEID: ' . $site_id,
            'X-EBAY-API-CALL-NAME: GetMyeBaySelling',
            'X-EBAY-API-IAF-TOKEN:' . $token,
            'Content-Type: text/xml;charset=utf-8',
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            return false;
        }

        curl_close($ch);

        $res = simplexml_load_string($response);
        $totalEntries = (int)$res->ActiveList->PaginationResult->TotalNumberOfEntries;

        return $totalEntries;
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
            foreach ($items as $item) {

                if (empty($item)) {
                    continue;
                }

                $token = isset($item['token']) ? $item['token'] : '';
                $title = isset($item['title']) ? $item['title'] : '';

                $trimmedTitle = substr($title, 0, 80);
                // $description = $item['description'];
                $description = isset($item['description']) ? $item['description'] : '';
                $listingType = $item['listingType'];
                $quantity = $item['quantity'];
                $startPrice = $item['startPrice'];

                $conditionID = $item['conditionID'];
                $conditionDisplayName = $item['ConditionDisplayName'];
                $category_id = $item['category_id'];
                $CategoryName = $item['CategoryName'];

                $catResp = $this->getAllCategory($target_site_id, $token, $apiUrl);
                $allList = $catResp->CategoryArray->Category;

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
                    // $api_key = 'a9d38188e39099c9d5ca586a8d157c4d';
                    // $api_endpoint = "http://data.fixer.io/api/latest?access_key=$api_key&base=$target_site_currency";

                    // $response = file_get_contents($api_endpoint);
                    // $data = json_decode($response, true);

                    //$target_currency_convertion = $data['rates'][$target_site_currency];

                    //echo $target_site_currency."=>".$target_currency_convertion;
                    if ($target_site_currency == "EUR") {
                        $convertion_rate = 1;
                    } else if ($target_site_currency == "USD") {
                        $convertion_rate = 1.1;
                    } else {
                        continue;
                    }
                    $final_price = $startPrice * $convertion_rate;

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

                    // Log::info($item['ItemSpecifics']);
                    //Log::info($requestxml);
                    // continue;
                    $curl = curl_init($apiUrl);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers_additem);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $requestxml);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response_data = curl_exec($curl);

                    if ($response_data === false) {
                        echo 'cURL Error: ' . curl_error($curl);
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
        // return $text;
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
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://google-translate1.p.rapidapi.com/language/translate/v2', [
                'form_params' => [
                    'q' => $text,
                    'target' => $targetLanguage,
                ],
                'headers' => [
                    'Accept-Encoding' => 'appl ication/gzip',
                    'X-RapidAPI-Host' => 'google-translate1.p.rapidapi.com',
                    'X-RapidAPI-Key' => 'de4eaa36d6msh5ab53fba6c8b3e3p118b8bjsn2ec1fd682c0b',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            // Access the translated text
            return $result['data']['translations'][0]['translatedText'];
        } catch (\Exception $e) {
            // Handle translation error
            return $text; // Return the original text if translation fails
        }
    }
}
