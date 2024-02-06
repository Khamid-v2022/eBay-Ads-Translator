<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Google\Cloud\Translate\V2\TranslateClient;
use App\Models\Tienda;

class ApiController extends Controller
{
    private $translate;

    public function __construct()
    {
        $this->translate = new TranslateClient([
            'keyFilePath' => public_path('assets/files/macro-gadget-392011-d71c24bdea62.json'),
        ]);
    }
    function index(){

        $appId = 'AspetTad-AdTransl-PRD-1fcac1c68-5bf3c305'; // Replace with your eBay App ID
        
        $devId = '86bb8d82-3676-41fa-9753-a768f440589d'; // Replace with your eBay Dev ID
        $certId = 'PRD-fcac1c68ce5d-43d1-4689-af0b-fee6'; // Replace with your eBay Cert ID
        $token = 'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA=='; // Replace with your eBay User Token
        //$token = 'v^1.1#i^1#I^3#p^3#r^1#f^0#t^Ul4xMF8xMDowRjY5NDNGMjMwQzk2QUVDNjA5QUQzNDBBM0RFQUIzOV8zXzEjRV4xMjg0';
        // eBay API endpoint
        $apiUrl = 'https://api.ebay.com/ws/api.dll';

        // Get the current time
        $currentTime = time();

        // Calculate the start time for the previous hour
        $startTime = strtotime('-4 hour', $currentTime);

        // Format the start and end times in ISO 8601 format
        $startTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $startTime);
        $endTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $currentTime);

        $headers = [
            'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
            'X-EBAY-API-SITEID: 186', // 0 for eBay US, update for other marketplaces
            'X-EBAY-API-CALL-NAME: GetItem',
            'X-EBAY-API-IAF-TOKEN: ' . $token,
            'Content-Type: text/xml;charset=utf-8',
        ];
        $itemId = '364588755100';
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
       
        // Initialize cURL session
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Make the API request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        $res = simplexml_load_string($response);
        echo "<pre>";
        print_r($res);
        die();
        //$items = $res->ItemArray->Item->toArray;
        $activeListings = [];

        foreach ($res->ItemArray->Item as $item) {

            $listingStatus = (string) $item->SellingStatus->ListingStatus;

            // Check if the listing is active
            if ($listingStatus === 'Active') {
               
                $activeListings[]= [
                    "title" => (string)$item->Title,
                    "description"=> (string)$item->Description,
                    "listingType"=>(string)$item->ListingType,
                    "quantity"=>(string)$item->Quantity,
                    "startPrice" => (string)$item->StartPrice,
                    "conditionID" => (string)$item->ConditionID,
                    "ConditionDisplayName" => (string)$item->ConditionDisplayName,
                    "category_id" => (string)$item->PrimaryCategory->CategoryID,
                    "CategoryName" => (string)$item->PrimaryCategory->CategoryName,
                    "country" => (string)$item->Country,
                    "currency" => (string)$item->Currency,
                ];
            }
        }
        $targetLanguage = 'de';

        foreach ($activeListings as &$listing) {
            // Translate the item data
            foreach ($listing as $key => $value) {
                $listing[$key] = $this->translateText($value, $targetLanguage);
            }
        }
        //print_r($activeListings);
        //$this->create($activeListings);
    }
    public function shipservice(){
        $Tienda = Tienda::first()->access_token;
        $access_token = $Tienda;
        //$access_token = 'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA==';
        $apiUrl = 'https://api.ebay.com/ws/api.dll';
        $selectedSiteId = $_GET['id'];
        $headers = [
            'X-EBAY-API-SITEID: '.$selectedSiteId,
            'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
            'X-EBAY-API-CALL-NAME: GeteBayDetails',
            'Content-Type: text/xml;charset=utf-8',
        ];
        $requestXmlBody = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <GeteBayDetailsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
            <RequesterCredentials>
                <eBayAuthToken>{$access_token}</eBayAuthToken>
            </RequesterCredentials>
            <ErrorLanguage>en_US</ErrorLanguage>
            <WarningLevel>High</WarningLevel>
            <DetailName>ShippingServiceDetails</DetailName>
        </GeteBayDetailsRequest>
        XML;
        $ch = curl_init($apiUrl);
        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXmlBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Make the API request
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            $res = simplexml_load_string($response);
            foreach($res->ShippingServiceDetails as $list){
                echo $list->ShippingService."<br>";
            }
        }
}
    function getproduct(){

        $token = 'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA==';  // Replace with your actual eBay authentication token
        // Verify that the token includes the necessary scope
        if (!strpos($token, 'https://api.ebay.com/oauth/api_scope')) {
            echo 'Error: The token is missing the required scope for Buy API.';
            // Regenerate a new token with the correct scope if needed
        }

        $apiUrl = 'https://api.ebay.com/buy/browse/v1/item/';
        $itemId = '364588755100';

        // Set your eBay authentication token
        $token = 'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA==';

        // Initialize cURL session
        $ch = curl_init($apiUrl . $itemId);

        // Set cURL options
        $headers = [
            'Authorization: Bearer '. $token,
            'Content-Type: application/json',
            'X-EBAY-C-SITE-ID: 186', // Use the correct site ID for eBay Spain (ES)
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Make the API request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $res = json_decode($response, true);
        print_r($res);
        // Check for errors in the response
        if (isset($res['errors'])) {
            foreach ($res['errors'] as $error) {
                echo 'Error: ' . $error['message'] . PHP_EOL;
            }
        } else {
            // Process the item details
            $item = $res['item'];

            $title = $item['title'];
            $description = $item['description'];

            // Output item details
            echo "Title: $title\n";
            echo "Description: $description\n";

            // Add more processing based on your requirements
        }

        

    }
    function getEbay(){
        $apiUrl = 'https://api.ebay.com/ws/api.dll'; 
        
        $headers_additem = [
            'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
            'X-EBAY-API-SITEID:77',
            'X-EBAY-API-CALL-NAME:AddItem',
            'X-EBAY-API-IAF-TOKEN:'.$token,
            'Content-Type: text/xml;charset=utf-8',
        ];
    }
    function create($activeListings){

        $apiUrl = 'https://api.ebay.com/ws/api.dll'; 
        
        $headers_additem = [
            'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
            'X-EBAY-API-SITEID:77',
            'X-EBAY-API-CALL-NAME:AddItem',
            'X-EBAY-API-IAF-TOKEN:'.$token,
            'Content-Type: text/xml;charset=utf-8',
        ];
       
        foreach($activeListings as $item){
                
                $title = $item['title'];
                $description = $item['description'];
                $listingType = $item['listingType'];
                $quantity = $item['quantity'];
                $startPrice = $item['startPrice'];
                $conditionID = $item['conditionID'];
                $conditionDisplayName = $item['ConditionDisplayName'];
                $category_id = $item['category_id'];
                $CategoryName = $item['CategoryName'];
                $country = $item['country'];

                $source_price = str_replace(',', '.', $startPrice);

                $converstionRateDe = "0.0117548";
                $finalpriceDE = $source_price * $converstionRateDe;
                
                $requestxml = <<<XML
                <?xml version="1.0" encoding="utf-8"?>
                <AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
                    <RequesterCredentials>
                        <eBayAuthToken>$token</eBayAuthToken>
                    </RequesterCredentials>
                    <ErrorLanguage>en_US</ErrorLanguage>
                    <WarningLevel>High</WarningLevel>
                    <Item>
                        <AutoPay>false</AutoPay>
                        <Country>DE</Country>
                        <Currency>EUR</Currency>
                        <ListingType>FixedPriceItem</ListingType>
                        <Location>10115 Berlin</Location>
                        
                        <PrimaryCategory>
                            <CategoryID>$category_id</CategoryID>
                        </PrimaryCategory>
                        <Title>$title</Title>
                        <Description>$description</Description>
                        <StartPrice>$finalpriceDE</StartPrice>
                        <Quantity>$quantity</Quantity>
                        <ConditionID>$conditionID</ConditionID>
                    
                        <ReturnPolicy>
                            <ReturnsAcceptedOption>ReturnsNotAccepted</ReturnsAcceptedOption>
                            <ReturnsAccepted>Keine Rückerstattung</ReturnsAccepted>
                        </ReturnPolicy>

                        <ShippingDetails>
                            <ShippingType>Flat</ShippingType>
                            <ShippingServiceOptions>
                                <ShippingServicePriority>1</ShippingServicePriority>
                                <ShippingService>DE_COD</ShippingService>
                                <ShippingServiceCost>0.0</ShippingServiceCost>
                                <FreeShipping>true</FreeShipping>
                            </ShippingServiceOptions>
                            <InternationalShippingDiscountProfileID>0</InternationalShippingDiscountProfileID>
                        </ShippingDetails>
                        
                        <ListingDuration>Days_7</ListingDuration>
                        <ListingType>FixedPriceItem</ListingType>
                        <ShipToLocations>DE</ShipToLocations>
                        <DispatchTimeMax>3</DispatchTimeMax>
                        <PictureDetails>
                            <PictureURL>https://i.pinimg.com/736x/90/a7/6a/90a76abde7c189f709938c461f92908c.jpg</PictureURL>
                        </PictureDetails>
                        
                    </Item>
                </AddItemRequest>
                XML;
                
                $curl= curl_init($apiUrl);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers_additem);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestxml);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response_data = curl_exec($curl);
                
                
                if ($response_data === false) {
                    echo 'cURL Error: ' . curl_error($curl);
                } else {
                    $xml_response = simplexml_load_string($response_data);
                    if ($xml_response->Ack == 'Success') {
                        echo 'Item successfully listed!';
                    } else {
                        dd($xml_response);
                    }
                }
                curl_close($curl);
           
        }
        
    }
    public function translateToGerman()
    {
        
        $data = [
            "title" => "Replace your tile",
            "description" => "The description of your ebay spain listing"
        ];

        // Assuming $response is a string or an array of strings
        $translatedResponse = $this->translateText($data, 'de');
        echo "<pre>";
        print_r($translatedResponse);
        // return view('your.view', compact('translatedResponse'));
    }
    private function translateText($text, $targetLanguage)
    {
       // If $text is an array, translate each element
       if (is_array($text)) {
            return array_map(function ($item) use ($targetLanguage) {
                return $this->translateText($item, $targetLanguage);
            }, $text);
        }

        // Translate the text
        $result = $this->translate->translate($text, [
            'target' => $targetLanguage,
        ]);

        // Return the translated text
        return $result['text'];
    }

}
