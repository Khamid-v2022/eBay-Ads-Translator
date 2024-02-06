<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/getaccount', function(){
    $url = 'https://api.ebay.com/sell/account/v1/fulfillment_policy?marketplace_id=EBAY_ES';
    $headers = [
        'Authorization: Bearer v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA==',
        'Content-Type: application/json',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);
    print_r($response);

    // $shippingPolicies = json_decode($response, true)['shippingPolicies'];

    // Handle $shippingPolicies as needed

}); 
Route::get('/shipservice',function(){
    
    $access_token = 'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul4xMF81OkYwNjJGMjRFMENCMjJDQzk1MzhBRENFRDJEOTQwRDhDXzNfMSNFXjI2MA==';

    $apiUrl = 'https://api.ebay.com/ws/api.dll';
    $selectedSiteId =$_GET['id'];
    $headers = [
        'X-EBAY-API-SITEID:'.$selectedSiteId,
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
    
});

Route::get('/create',function(){
    $client_id = 'AspetTad-AdTransl-PRD-1fcac1c68-5bf3c305';
    $client_secret = 'PRD-fcac1c68ce5d-43d1-4689-af0b-fee6';
    $access_token = 'v^1.1#i^1#f^0#I^3#p^3#r^1#t^Ul4xMF8xOjk4QTEwMzI0REVGNkI2QkZCRjcwRUQ1NTNFRjYxNDc3XzNfMSNFXjI2MA==';

    $api_url = 'https://api.ebay.com/ws/api.dll'; // The correct eBay API endpoint for AddItem

    // Headers
$headers = [
    'X-EBAY-API-COMPATIBILITY-LEVEL: 1155',
    'X-EBAY-C-MARKETPLACE-ID = EBAY_US',
    'X-EBAY-API-CALL-NAME: AddItem',
    'X-EBAY-API-IAF-TOKEN: ' . $access_token,
    'Content-Type: text/xml;charset=utf-8',
];

// Create a unique ItemID (you may use a different method to generate a unique ID)
$item_id = uniqid();

// Request XML for AddItem call
$request_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
    <RequesterCredentials>
        <eBayAuthToken>$access_token</eBayAuthToken>
    </RequesterCredentials>
    <ErrorLanguage>en_US</ErrorLanguage>
    <WarningLevel>High</WarningLevel>
    <Item>
        <AutoPay>false</AutoPay>
        <Country>US</Country>
        <Currency>USD</Currency>
        <ListingType>FixedPriceItem</ListingType>
        <Location>14900 Los Santos, Spain</Location>
        <PaymentMethods>PayPal</PaymentMethods>
        <PrimaryCategory>
            <CategoryID>6028</CategoryID>
        </PrimaryCategory>
        <Title>Samsung Galaxy S23 SM-S911U - 256GB - Negro Fantasma (Desbloqueado)</Title>
        <Description>Samsung Galaxy S23 SM-S911U - 256GB - Negro Fantasma (Desbloqueado)</Description>
        <StartPrice>100.00</StartPrice>
        <Quantity>1</Quantity>
        <ConditionID>1000</ConditionID>
        <DispatchTimeMax>3</DispatchTimeMax>
        <ListingDuration>GTC</ListingDuration>
        <ReturnPolicy>
            <ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
            <RefundOption>MoneyBack</RefundOption>
            <ReturnWithin>30</ReturnWithin>
            <ShippingCostPaidBy>Buyer</ShippingCostPaidBy>
        </ReturnPolicy>
        <!-- Add more item details here -->
    </Item>
</AddItemRequest>
XML;

// Initialize cURL session for the eBay API
$ch = curl_init($api_url);

// Set cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request_xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

// Handle the API response
if ($response === false) {
    // Handle cURL error
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Process the API response (eBay's response will be in XML format)
    // You can parse the XML response here and handle any errors or success cases
    // Example: Use an XML parser like SimpleXML to parse the response.
    $xml_response = simplexml_load_string($response);
    
    // Check for success or error in the response
    if ($xml_response->Ack == 'Success') {
        echo 'Item successfully listed!';
    } else {
        echo 'Error: ' . $xml_response->Errors->LongMessage;
    }
}

// Close the cURL session for the eBay API
curl_close($ch);

});

Route::get('/getlist', [ApiController::class, 'index'])->name('api.getlist');
Route::get('/getp', [ApiController::class, 'getproduct'])->name('api.getp');
Route::get('/translateToGerman', [ApiController::class, 'translateToGerman']);
Route::get('/apicall',function(){
    
        // Your eBay API credentials
        $appId = 'AspetTad-AdTransl-PRD-1fcac1c68-5bf3c305'; // Replace with your eBay App ID
        date_default_timezone_set('Asia/Karachi');
        // Get timestamps
        // Get the current time
        $currentTime = time();

        // Calculate the start time for the previous hour
        $startTime = strtotime('-1 hour', $currentTime);

        // Format the start and end times in ISO 8601 format
        $startTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $startTime);
        $endTimeISO8601 = date('Y-m-d\TH:i:s.v\Z', $currentTime);

        echo "Start Time: $startTimeISO8601\n";
        echo "End Time: $endTimeISO8601\n";
        exit();
        // eBay API endpoint
        $apiUrl = 'https://svcs.ebay.com/services/search/FindingService/v1';

        // Request headers
        $headers = [
            'X-EBAY-SOA-SECURITY-APPNAME: ' . $appId,
            'X-EBAY-SOA-OPERATION-NAME: findItemsAdvanced'
        ];

        // Request XML for findItemsAdvanced call with time range filter
        $request_xml = <<<XML
        <?xml version="1.0" encoding="utf-8"?>
        <findItemsAdvancedRequest xmlns="http://www.ebay.com/marketplace/search/v1/services">
            <itemFilter>
                <name>ListingType</name>
                <value>FixedPrice</value>
            </itemFilter>
            <sortOrder>StartTimeNewest</sortOrder>
            <itemFilter>
                <name>EndTimeFrom</name>
                <value>$startTimeISO</value>
            </itemFilter>
            <itemFilter>
                <name>EndTimeTo</name>
                <value>$endTimeISO</value>
            </itemFilter>
            <paginationInput>
                <entriesPerPage>10</entriesPerPage>
                <pageNumber>1</pageNumber>
            </paginationInput>
        </findItemsAdvancedRequest>
        XML;

        // Initialize cURL session
        $ch = curl_init($apiUrl);

        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Make the API request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Process the response (you might want to use an XML parser)
        echo $response;

        
});