<?php
$apiKey = "rM3lBHSe7T0lGntdtKuSZo7npAWKrBFk";       // from your DHL app
$apiSecret = "S0fWCFeAZRXEZTZ9"; // from your DHL app
$accountNumber = "189166"; // from your mydhl_test_app page

// Encode API credentials for Basic Auth
$auth = base64_encode("$apiKey:$apiSecret");

// Example: Get Rates
$url = "https://api-mock.dhl.com/mydhlapi/rates?" . http_build_query([
    "accountNumber" => $accountNumber,
    "originCountryCode" => "DE",
    "originCityName" => "Berlin",
    "destinationCountryCode" => "US",
    "destinationCityName" => "New York",
    "weight" => 5,
    "length" => 10,
    "width" => 10,
    "height" => 10,
    "plannedShippingDate" => date("Y-m-d", strtotime("+1 day")),
    "isCustomsDeclarable" => "false",
    "unitOfMeasurement" => "metric"
]);

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic $auth",
        "Content-Type: application/json",
        "Accept: application/json"
    ]
]);

$response = curl_exec($curl);
if ($response === false) {
    echo "cURL Error: " . curl_error($curl);
} else {
    echo "<pre>";
    print_r(json_decode($response, true));
    echo "</pre>";
}

curl_close($curl);
?>
