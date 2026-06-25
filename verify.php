<?php
session_start();
require("razorpay-php-master/Razorpay.php");
include("config/config.php");

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['razorpay_payment_id']) && !empty($data['razorpay_order_id']) && !empty($data['razorpay_signature'])) {
    try {
        $attributes = [
            'razorpay_order_id'   => $data['razorpay_order_id'],
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature'  => $data['razorpay_signature']
        ];

        $api->utility->verifyPaymentSignature($attributes);

        // Update DB
        $stmt = $conn->prepare("UPDATE orders SET payment_id=?, signature=?, status='paid' WHERE order_id=?");
        $stmt->bind_param("sss", $data['razorpay_payment_id'], $data['razorpay_signature'], $data['razorpay_order_id']);
        $stmt->execute();

        echo "Payment verified";
    } catch(Exception $e) {
        $stmt = $conn->prepare("UPDATE orders SET status='failed' WHERE order_id=?");
        $stmt->bind_param("s", $data['razorpay_order_id']);
        $stmt->execute();
        echo "Payment verification failed: " . $e->getMessage();
    }
}
