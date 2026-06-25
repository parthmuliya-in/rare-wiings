<?php
/**
 * Shiprocket PHP Integration Example
 * Steps: Authenticate → Rate Check → Create Order → Track Shipment
 */

// Your API user credentials (from Shiprocket Dashboard → Settings → API → Configure)
define("SR_EMAIL", "isourcingsolutions.backup@gmail.com");
define("SR_PASSWORD", "Phone@9876");

// Shiprocket API base URL
define("SR_BASE_URL", "https://apiv2.shiprocket.in/v1/external");

// 🔹 1. Authenticate and get token
function sr_authenticate() {
    $url = SR_BASE_URL . "/auth/login";
    $payload = json_encode([
        "email" => SR_EMAIL,
        "password" => SR_PASSWORD
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($resp, true);
    if (!isset($data['token'])) {
        die("Auth failed: " . $resp);
    }
    return $data['token'];
}

// 🔹 2. Rate Check (Courier Serviceability)
function sr_rate_check($token, $pickup_pincode, $delivery_pincode, $weight = 0.5) {
    $url = SR_BASE_URL . "/courier/serviceability";
    $payload = json_encode([
        "pickup_postcode" => $pickup_pincode,
        "delivery_postcode" => $delivery_pincode,
        "cod" => 1,
        "weight" => $weight
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $resp = curl_exec($ch);
    curl_close($ch);

    return json_decode($resp, true);
}

// 🔹 3. Create Order
function sr_create_order($token) {
    $url = SR_BASE_URL . "/orders/create/shipments";
    $orderData = [
        "order_id" => "ORDER123",
        "order_date" => date("Y-m-d H:i"),
        "pickup_location" => "Primary", // configure in dashboard
        "channel_id" => "",
        "comment" => "Test order",
        "billing_customer_name" => "John",
        "billing_last_name" => "Doe",
        "billing_address" => "123 Test Street",
        "billing_city" => "Mumbai",
        "billing_pincode" => "400001",
        "billing_state" => "Maharashtra",
        "billing_country" => "India",
        "billing_email" => "john@example.com",
        "billing_phone" => "9999999999",
        "shipping_is_billing" => true,
        "payment_method" => "Prepaid",
        "sub_total" => 500,
        "length" => 10,
        "breadth" => 10,
        "height" => 5,
        "weight" => 0.5,
        "order_items" => [
            [
                "name" => "Test Product",
                "sku" => "SKU001",
                "units" => 1,
                "selling_price" => 500,
                "discount" => 0,
                "tax" => 0,
                "hsn" => "441122"
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    $resp = curl_exec($ch);
    curl_close($ch);

    return json_decode($resp, true);
}

// 🔹 4. Track Shipment
function sr_track_order($token, $awb) {
    $url = SR_BASE_URL . "/courier/track/awb/" . $awb;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);

    return json_decode($resp, true);
}

// -------------------- USAGE --------------------
$token = sr_authenticate();
echo "✅ Authenticated. Token: $token\n\n";

// Example: Check rates
$rates = sr_rate_check($token, "400001", "110001", 0.5);
echo "✅ Rate check result:\n";
print_r($rates);

// Example: Create order
$order = sr_create_order($token);
echo "✅ Order created:\n";
print_r($order);

// Example: Track shipment (if you have AWB from create order response)
if (isset($order['shipment_id'])) {
    $awb = $order['shipments'][0]['awb'] ?? null;
    if ($awb) {
        $tracking = sr_track_order($token, $awb);
        echo "✅ Tracking result:\n";
        print_r($tracking);
    }
}
