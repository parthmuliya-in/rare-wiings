<?php
// cart.php (corrected version)
include("header.php"); // assume this sets $conn and session_start()

// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$cart = $_SESSION['cart'] ?? [];

// redirect if cart empty
if (empty($cart)) {
  header("Location: product.php");
  exit();
}

// messages to show to user (success / error)
$messages = [];

// 1) Handle quantity update (auto-submit from number input)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qty_update'])) {
  $index = isset($_POST['index']) ? (int) $_POST['index'] : 0;
  $qty = max(1, (int) ($_POST['quantity'] ?? 1));
  if (isset($_SESSION['cart'][$index])) {
    $_SESSION['cart'][$index]['quantity'] = $qty;
  }
  // reload to show updated totals
  header("Location: cart.php");
  exit();
}

// 2) If user clicked the "Apply" promo button (manual attempt)
$appliedPromo = null;

// 4) Compute subtotal/total (do this once)
$total = 0;
$items_for_display = []; // keep items and per-item subtotal for display


foreach ($cart as $index => $item) {
  $price = isset($item['price']) ? (float) $item['price'] : 0;
  $qty = isset($item['quantity']) ? (int) $item['quantity'] : 1;
  $subtotal = $price * $qty;

  // ✅ decide which color to show
  $color_to_show = !empty($item['color']) ? $item['color'] : ($item['main_color'] ?? '');

  $items_for_display[$index] = [
    'index' => $index,
    'image' => $item['image'] ?? '',
    'title' => $item['title'] ?? '',
    'size' => $item['size'] ?? '',
    'color' => $color_to_show,
    'price' => $price,
    'quantity' => $qty,
    'subtotal' => $subtotal
  ];
  $total += $subtotal;
}
// 6) Fetch latest active coupon (same logic you used)
$coupon = null;
$coupon_dis = 0;
$coupon_val = 0;
$coupon_sql = "SELECT * FROM coupons WHERE status=1 AND expiry_date >= CURDATE() ORDER BY id DESC LIMIT 1";
$coupon_result = mysqli_query($conn, $coupon_sql);
if ($coupon_result && mysqli_num_rows($coupon_result) > 0) {
  $coupon = mysqli_fetch_assoc($coupon_result);
  $coupon_dis = (float) $coupon['discount_percent'];
  $coupon_val = $total * $coupon_dis / 100;
}

// 9) Grand total (independent discounts)
//$grand_total = $total - $coupon_val - $promo_val - 10;
$grand_total = $total - $coupon_val;
if ($grand_total < 0)
  $grand_total = 0; // sanity

// (Optional) Round nicely
$total = round($total, 2);
$coupon_val = round($coupon_val, 2);
//$promo_val = round($promo_val, 2);
$grand_total = round($grand_total, 2);

//Chatbot implimentation here
include("chatbot.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>My Cart</title>
</head>

<body>

  <div class="container">
    <!-- Cart Items -->
    <div class="cart-items">
      <h2>My Cart</h2> <a href="product.php">Add One</a>

      <!-- display any messages -->
      <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $m): ?>
          <div style="margin:8px 0; padding:8px; background:#fff5c2; border-radius:4px;"><?= htmlspecialchars($m) ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php foreach ($items_for_display as $index => $it): ?> 
        <div class="cart-item" data-index="<?= $index ?>">
          <img src="admin/uploads/<?= htmlspecialchars($it['image']) ?>" alt="<?= htmlspecialchars($it['title']) ?>">
          <div class="item-details">
            <p><b><?= htmlspecialchars($it['title']) ?></b></p>
            <p>Size: <?= htmlspecialchars($it['size']) ?> | Color: <?= htmlspecialchars($it['color']) ?></p>
            <p>Price: <i class="fa-solid fa-indian-rupee-sign"></i><?= number_format($it['price'], 2) ?></p>
            <p>Subtotal: <i class="fa-solid fa-indian-rupee-sign"></i><span class="item-subtotal"><?= number_format($it['subtotal'], 2) ?></span></p>
          </div>
          <div>
            <!-- Auto-update form -->
            <form method="post" action="cart.php">
              <input type="hidden" name="index" value="<?= $index ?>">
              <input type="number" name="quantity" value="<?= $it['quantity'] ?>" min="1" class="qty-input"
                onchange="this.form.submit()">
              <input type="hidden" name="qty_update" value="1">
            </form>
          </div>
          <a href="remove_cart.php?index=<?= $index ?>" class="remove-btn"><i class="fa fa-trash"></i></a>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
      <h3>Order Summary</h3>

      <div class="summary-row">
        <span>Subtotal:</span> <span id="subtotal"><i class="fa-solid fa-indian-rupee-sign"></i><?= number_format($total, 2) ?></span>
      </div>

      <div class="summary-row">
        <span>
          <i class="fa-solid fa-ticket" style="color: #0a0a09ff;"></i>
          <input type="text" id="activeCoupon"
            value="<?= $coupon ? htmlspecialchars($coupon['code']) : 'No Active Coupon' ?>" readonly
            style="background:#f2f2f2; font-weight:bold; border: 1px #000 dashed;">
        </span>
        <span id="tax"><i class="fa-solid fa-indian-rupee-sign "></i><?= number_format($_SESSION['coupon_val'] = $coupon_val, 2) ?></span>
      </div>

      <div class="summary-row">
        <b>Total:</b> <b id="totalPrice"><i class="fa-solid fa-indian-rupee-sign" ></i><?= number_format($_SESSION['grand_total'] = $grand_total, 2) ?></b>
      </div>
      <form id="checkoutForm" action="order.php" method="post">
        <button type="button" class="btn" id="checkoutBtn">Proceed to Checkout</button>
      </form>

      <!-- Popup Modal -->
      <div id="checkoutModal" class="modal">
        <div class="modal-content">
          <button class="close-btn" onclick="closeModal()">&times;</button>
          <p><b>Care for Your Rare Wiing</b><br></p>
          To keep your Rare Wiing pieces soft, durable, and looking new:
          <ul>
            <li><i class="fa-solid fa-snowflake" style="color: #74C0FC;"></i> <b>Wash cold:</b> Hand wash or machine wash on gentle cycle with cold water.</li>
            <li><i class="fa-solid fa-jug-detergent" style="color: #f278b3;"></i> <b>Mild detergent only:</b> Avoid bleach or harsh chemicals.</li>
            <li><i class="fa-solid fa-wind" style="color: #74C0FC;"></i> <b>Air dry:</b> Hang or lay flat to dry. Avoid tumble drying.</li>
            <li><i class="fa-solid fa-temperature-low" style="color: #f13304;"></i> <b>Cool iron:</b> If needed, iron inside out on low heat.</li>
          </ul>
          <p><i class="fa-solid fa-tree" style="color: green;"></i> Bamboo fabrics are naturally soft and eco-friendly. Treat them right, and they'll stay rare for years.
          </p>

          <!-- Confirm button triggers form submit -->
          <button id="confirmCheckout" class="proceed-btn">Yes, Proceed</button>
        </div>
      </div>

      <style>
        /* Modal background */
        .modal {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.6);
          justify-content: center;
          align-items: center;
          z-index: 9999;
        }

        /* Modal box */
        .modal-content {
          position: relative;
          background: #fff;
          padding: 30px 20px;
          border-radius: 10px;
          text-align: center;
          max-width: 600px;
          width: 90%;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
          font-family: Arial, sans-serif;
        }

        /* Cancel (close) button */
        .close-btn {
          position: absolute;
          top: 10px;
          right: 15px;
          font-size: 20px;
          font-weight: bold;
          color: #666;
          background: none;
          border: none;
          cursor: pointer;
          transition: 0.2s;
        }

        .close-btn:hover {
          color: #e74c3c;
        }

        /* Proceed button */
        .proceed-btn {
          display: inline-block;
          margin-top: 20px;
          padding: 10px 20px;
          background: #101010ff;
          color: #fff;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          font-size: 16px;
          transition: background 0.3s;
        }

        .proceed-btn:hover {
          background: #363636ff;
        }
      </style>

      <script>
        const checkoutBtn = document.getElementById("checkoutBtn");
        const confirmCheckout = document.getElementById("confirmCheckout");
        const checkoutForm = document.getElementById("checkoutForm");

        checkoutBtn.addEventListener("click", function () {
          openModal();
        });

        confirmCheckout.addEventListener("click", function () {
          checkoutForm.submit(); // submits the form to order.php
        });

        function openModal() {
          document.getElementById("checkoutModal").style.display = "flex";
        }
        function closeModal() {
          document.getElementById("checkoutModal").style.display = "none";
        }
        window.onclick = function (e) {
          if (e.target == document.getElementById("checkoutModal")) {
            closeModal();
          }
        }
      </script>



    </div>
  </div>

  <?php include("footer.php"); ?>
</body>

</html>