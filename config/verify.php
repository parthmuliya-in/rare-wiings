<?php
session_start();
require("razorpay-php-master/Razorpay.php");
include("config/config.php"); // DB connection

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

// Razorpay Test Keys (replace with your real keys)
$keyId     = "rzp_test_RIFafEnGmhFF4V";
$keySecret = "iz8m99FyYimsyFkESzBm2z52";

$api = new Api($keyId, $keySecret);

// Get JSON data from Razorpay handler
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo "No data received";
    exit;
}

$paymentId = $input['razorpay_payment_id'] ?? null;
$orderId   = $input['razorpay_order_id'] ?? null;
$signature = $input['razorpay_signature'] ?? null;

try {
    // Verify signature
    $attributes = [
        'razorpay_order_id'   => $orderId,
        'razorpay_payment_id' => $paymentId,
        'razorpay_signature'  => $signature
    ];

    $api->utility->verifyPaymentSignature($attributes);

    // ✅ Update DB with payment_id + signature
    $stmt = $conn->prepare("UPDATE orders SET payment_id=?, signature=?, status='paid' WHERE order_id=?");
    $stmt->bind_param("sss", $paymentId, $signature, $orderId);
    $stmt->execute();

    echo "Payment verified successfully!";
} catch (SignatureVerificationError $e) {
    // If signature check fails
    $stmt = $conn->prepare("UPDATE orders SET status='failed' WHERE order_id=?");
    $stmt->bind_param("s", $orderId);
    $stmt->execute();

    echo "Payment verification failed: " . $e->getMessage();
}
?>
