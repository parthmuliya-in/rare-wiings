<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

// 🧠 Helper function to safely count table rows
function getTableCount($conn, $table) {
  $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM `$table`");
  if ($stmt && $stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['cnt'] ?? 0;
  }
  return 0;
}

// ✅ Secure counts
$order_count      = getTableCount($conn, "orders");
$product_count    = getTableCount($conn, "products");
$user_count       = getTableCount($conn, "users");
$subscriber_count = getTableCount($conn, "subscribe");
$coupon_count     = getTableCount($conn, "coupons");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 🧱 Styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="css/chatbot.css">
</head>
<body class="admin-dashboard-body">

  <div class="dashboard-container">
    
    <!-- 🧭 Header -->
    <div class="dashboard-header">
      <h2><i class="fa-solid fa-chart-simple"></i> Admin Dashboard</h2>
    </div>

    <!-- 📊 Dashboard Cards -->
    <div class="dashboard-cards">

      <div class="dashboard-card">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-money-bills"></i> Orders</h5>
        <p class="dashboard-card-count"><?= htmlspecialchars($order_count) ?></p>
        <a href="view_order.php" class="dashboard-card-btn">View Orders</a>
      </div>

      <div class="dashboard-card">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-box-open"></i> Products</h5>
        <p class="dashboard-card-count"><?= htmlspecialchars($product_count) ?></p>
        <a href="manage_products.php" class="dashboard-card-btn">Manage Products</a>
      </div>

      <div class="dashboard-card">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-user-plus"></i> Users</h5>
        <p class="dashboard-card-count"><?= htmlspecialchars($user_count) ?></p>
        <a href="user.php" class="dashboard-card-btn">Manage Users</a>
      </div>

      <div class="dashboard-card">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-user-group"></i> Subscribers</h5>
        <p class="dashboard-card-count"><?= htmlspecialchars($subscriber_count) ?></p>
        <a href="subscriber.php" class="dashboard-card-btn">View Subscribers</a>
      </div>

      <div class="dashboard-card">
        <h5 class="dashboard-card-title"><i class="fa-solid fa-ticket-simple"></i> Coupon Codes</h5>
        <p class="dashboard-card-count"><?= htmlspecialchars($coupon_count) ?></p>
        <a href="coupons.php" class="dashboard-card-btn">Manage Coupons</a>
      </div>

    </div>
  </div>

</body>
</html>
<?php $conn->close(); ?>
