<?php
session_start();
require("razorpay-php-master/Razorpay.php");
include("config/config.php");

use Razorpay\Api\Api;

// --- Check login ---
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$api = new Api($keyId, $keySecret);

// Example cart (replace with DB/session)
$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}

// Apply coupon if any
$grandTotal = $subtotal - ($_SESSION['coupon_val'] ?? 0);
$_SESSION['grand_total'] = $grandTotal;

// ✅ Convert amount to paise (integer only)
$grandTotalPaise = (int) round($grandTotal * 100);
/**
 * move on live server it will chanage like this
 * $grandTotalFloat = round($grandTotal, 2); // 19996.40
 * also change column type amount(int)(11) to float(11)
 */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $city = trim($_POST['city']);
  $state = trim($_POST['state']);
  $pincode = trim($_POST['pincode']);
  $address = trim($_POST['address']);

  if (empty($name) || empty($email) || empty($phone) || empty($address)) {
    $error = "Please fill all required fields.";
  } else {
    // ✅ Build item description from cart
    $itemNames = array_map(function ($item) {
      return $item['title'] . " x " . $item['quantity']. " = " . $item['price'];
    }, $cart);
    $description = implode(", ", $itemNames);

    // --- Create Razorpay Order ---
    $orderData = [
      'receipt' => 'ORD_' . time(),
      'amount' => $grandTotalPaise,   // ✅ integer
      'currency' => 'INR',
      'payment_capture' => 1,
      'notes' => [
        'user_id' => $_SESSION['user_id'],
        'items' => $description
      ]
    ];
    $razorpayOrder = $api->order->create($orderData);
    $order_id = $razorpayOrder['id'];

    // ✅ Insert into DB with items
    $stmt = $conn->prepare("INSERT INTO orders 
        (user_id, order_id, customer_name, customer_email, customer_phone, city, state, pincode, address, items, amount, status, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");

    $stmt->bind_param(
      "isssssssssi",
      $_SESSION['user_id'],
      $order_id,
      $name,
      $email,
      $phone,
      $city,
      $state,
      $pincode,
      $address,
      $description,
      $grandTotal
    );

    if ($stmt->execute()) {
      $_SESSION['order_id'] = $order_id;
      $_SESSION['customer_name'] = $name;
      $_SESSION['customer_email'] = $email;
      $_SESSION['customer_phone'] = $phone;
      $startPayment = true;
    } else {
      $error = "Database error: " . $stmt->error;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link rel="icon" type="image/x-icon" href="images/logo/2-01.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
  integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
 <style>
/* Base Layout */
.checkout-body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f4f4f9;
  margin: 0;
  overflow-x: hidden; /* prevent horizontal scrollbar */
}

.checkout-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  padding: 20px;
  max-width: 1200px;
  margin: auto;
  box-sizing: border-box;
  width: 100%;
}

/* Form & Summary Sections */
.checkout-form-section,
.checkout-summary-section {
  flex: 1;
  min-width: 320px;
  background: #fff;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
  box-sizing: border-box;
}

/* Titles */
.checkout-title {
  margin-bottom: 15px;
  font-size: 22px;
  color: #222;
}

/* Labels */
.checkout-label {
  display: block;
  margin: 10px 0 5px;
  font-weight: 600;
  font-size: 14px;
}

/* Inputs and Textareas */
.checkout-input,
.checkout-textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 15px;
  transition: border 0.3s;
  box-sizing: border-box;
  word-break: break-word;
}

.checkout-input:focus,
.checkout-textarea:focus {
  border-color: #528FF0;
  outline: none;
}

/* Button */
.checkout-btn {
  width: 100%;
  padding: 14px;
  background: #222;
  color: #fff;
  font-size: 16px;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}

.checkout-btn:hover {
  background: #444;
}

/* Table */
.checkout-table {
  table-layout: fixed;
  word-wrap: break-word;
  overflow-wrap: break-word;
  max-width: 100%;
  width: 100%;
  border-collapse: collapse;
}

.checkout-th,
.checkout-td {
  padding: 10px;
  border-bottom: 1px solid #eee;
  font-size: 15px;
  text-align: left;
  word-break: break-word;
}

.checkout-total-item {
  font-weight: bold;
  text-align: right;
  background: #fafafa;
}

.checkout-total-val {
  font-weight: bold;
  background: #fafafa;
}

/* Responsive: Tablets */
@media (max-width: 992px) {
  .checkout-container {
    flex-direction: column;
  }

  .checkout-form-section,
  .checkout-summary-section {
    padding: 30px;
  }

  .checkout-th,
  .checkout-td {
    font-size: 14px;
    padding: 8px;
  }
}

/* Responsive: Mobile */
@media (max-width: 600px) {
  .checkout-form-section,
  .checkout-summary-section {
    padding: 15px;
    border-radius: 8px;
  }

  .checkout-title {
    font-size: 20px;
  }

  .checkout-input,
  .checkout-textarea {
    font-size: 14px;
    padding: 10px;
  }

  .checkout-btn {
    font-size: 15px;
    padding: 12px;
  }

  .checkout-th,
  .checkout-td {
    font-size: 13px;
    padding: 6px;
  }
}

/* Responsive: Small Mobile */
@media (max-width: 400px) {
  .checkout-title {
    font-size: 18px;
  }

  .checkout-input,
  .checkout-textarea {
    font-size: 13px;
    padding: 8px;
  }

  .checkout-btn {
    font-size: 14px;
    padding: 10px;
  }

  .checkout-th,
  .checkout-td {
    font-size: 12px;
    padding: 5px;
  }
}

/* Extra: Prevent table overflow on very small screens */
@media (max-width: 360px) {
  .checkout-table {
    font-size: 12px;
  }

  .checkout-th,
  .checkout-td {
    padding: 4px;
    word-break: break-word;
  }
}
</style>

</head>

<body>

  <div class="checkout-container">
    <!-- LEFT SIDE: User Form -->
    <div class="checkout-form-section">
      <h2 class="checkout-title">Billing Details</h2>
      <?php if (!empty($error))
        echo "<p style='color:red;'>$error</p>"; ?>
      <form method="post">
        <label class="checkout-label">Name *</label>
        <input type="text" name="name" required class="checkout-input">

        <label class="checkout-label">Email *</label>
        <input type="email" name="email" required class="checkout-input">

        <label class="checkout-label">Contact *</label>
        <input type="text" name="phone" required class="checkout-input">

        <label class="checkout-label">City</label>
        <input type="text" name="city" class="checkout-input" required>

        <label class="checkout-label">State</label>
        <input type="text" name="state" class="checkout-input" required>

        <label class="checkout-label">Pincode</label>
        <input type="text" name="pincode" class="checkout-input" required>

        <label class="checkout-label">Address *</label>
        <textarea name="address" rows="3" required class="checkout-textarea"></textarea>

        <button class="checkout-btn" type="submit" name="checkout">Proceed to Pay</button>
      </form>
    </div>

    <!-- RIGHT SIDE: Order Summary -->
    <div class="checkout-summary-section">
      <h2 class="checkout-title">Order Summary</h2>
      <table class="checkout-table">
        <thead>
          <tr>
            <th class="checkout-th">Product</th>
            <th class="checkout-th">Price</th>
            <th class="checkout-th">Qty</th>
            <th class="checkout-th">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item): ?>
            <tr>
              <td class="checkout-td" data-label="Product"><?= htmlspecialchars($item['title']) ?></td>
              <td class="checkout-td" data-label="Price"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($item['price'], 2) ?></td>
              <td class="checkout-td" data-label="Qty"><?= $item['quantity'] ?></td>
              <td class="checkout-td" data-label="Subtotal"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($item['price'] * $item['quantity'], 2) ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="3" class="checkout-total-item" data-label="Coupon Discount">Coupon Discount :</td>
            <td class="checkout-total-val" data-label="Value"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($_SESSION['coupon_val'] ?? 0, 2) ?>
            </td>
          </tr>
          <tr>
            <td colspan="3" class="checkout-total-item" data-label="Grand Total">Grand Total :</td>
            <td class="checkout-total-val" data-label="Value"><i class="fa-solid fa-indian-rupee-sign"></i> <?= number_format($grandTotal, 2) ?></td>
          </tr>
        </tbody>

      </table>
    </div>
  </div>

  <?php if (isset($startPayment) && $startPayment): ?>
    <script>
      var options = {
        "key": "<?= $keyId ?>",
        "amount": "<?= $grandTotalPaise ?>", // ✅ integer paise
        "currency": "INR",
        "name": "Rare Wiing",
        "description": "<?= $description ?>",
        "order_id": "<?= $_SESSION['order_id'] ?>",
        "handler": function (response) {
          fetch("verify.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              razorpay_payment_id: response.razorpay_payment_id,
              razorpay_order_id: response.razorpay_order_id,
              razorpay_signature: response.razorpay_signature
            })
          })
            .then(res => res.text())
            .then(data => {
              window.location.href = "thankyou.php";
            });
        },
        "prefill": {
          "name": "<?= $_SESSION['customer_name'] ?>",
          "email": "<?= $_SESSION['customer_email'] ?>",
          "contact": "<?= $_SESSION['customer_phone'] ?>"
        },
        "theme": {
          "color": "#528FF0"
        }
      };
      var rzp1 = new Razorpay(options);
      rzp1.open();
    </script>
  <?php endif; ?>

</body>

</html>