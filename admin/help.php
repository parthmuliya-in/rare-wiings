<?php
session_start();
include("header.php");
// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Panel Instructions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="stylesheet" href="css/chatbot.css">
</head>

<body>
  <div class="container my-5">
    <h1>Admin Panel Instructions</h1>
    <div class="row">

      <!-- Manage Products -->
      <div class="col-md-4">
        <div class="card">
          <h3><i class="fa-solid fa-box-open" style="color: #e88131ff;"></i> Manage Products</h3>
          <ol>
            <li>Products with size should be capital, do not use at last, any special character or extra space in this field.</li>
            <li>
              <table>
                <tr>
                  <th>Category+</th>
                  <th>ProductCode+</th>
                  <th>Variant</th>
                </tr>
                <tr>
                  <td>TSH +</td>
                  <td>001 +</td>
                  <td>BLK-M</td>
                </tr>
                <tr>
                  <td colspan="3">SKU = TSH-001-BLK-M</td>
                </tr>
              </table>
            </li>
            <li>Edit existing product details also consider point, only click this image field that you will change.</li>
            <li>Delete products that are no longer available.</li>
            <li>Keep product list updated for customers.</li>
          </ol>
        </div>
      </div>

      <!-- Manage Coupon Codes -->
      <div class="col-md-4">
        <div class="card">
          <h3><i class="fa-solid fa-ticket-simple" style="color: #FFD43B;"></i> Manage Coupon Codes</h3>
          <ol>
            <li>Create coupon codes with flat or percentage discounts.</li>
            <li>Set expiry dates for each coupon.</li>
            <li>Enable/disable coupons when required.</li>
            <li>Use coupons to attract and retain customers.</li>
          </ol>
        </div>
      </div>

    </div>

    <div class="mt-4 text-center">
      <p class="text-muted">Tip: Always check your changes before going live.</p>
    </div>
  </div>
</body>

</html>
