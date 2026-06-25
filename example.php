<?php
//session_start();
require_once "config/config.php";
include "header.php";
include "chatbot.php";

// ✅ Check login
if (empty($_SESSION['user_id'])) {
  $_SESSION['flash'] = ['type' => 'error', 'text' => 'Please log in to continue.'];
  header("Location: login.php");
  exit;
}

$user_id = (int) $_SESSION['user_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $msg = "<i class='fa-solid fa-triangle-exclamation'></i> Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<i class='fa-solid fa-circle-xmark'></i> Invalid email address.";
    } else {
        // ✅ Prepare and handle error properly
        $stmt = $conn->prepare("UPDATE users SET fname = ?, email = ? WHERE id = ?");

        if (!$stmt) {
            die("<b>SQL Prepare Failed:</b> " . $conn->error);
        }

        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $msg = "<i class='fa-solid fa-circle-check'></i> Profile updated successfully!";
        } else {
            $msg = "<i class='fa-solid fa-bug'></i> Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}


// ✅ Fetch User Info
$stmt_user = $conn->prepare("SELECT id, fname, email, created_at FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// ✅ Fetch Orders
$stmt_orders = $conn->prepare("SELECT id, amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();
$stmt_orders->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <!-- ✅ Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<style>
    body {
  font-family: 'Poppins', sans-serif;
  background: #f9f9f9;
  padding: 20px;
  color: #333;
}

.dashboard h2 {
  color: #2c3e50;
  font-weight: 600;
  margin-bottom: 20px;
}

.message-box {
  background: #eef9ee;
  border-left: 5px solid #27ae60;
  color: #2c3e50;
  padding: 12px 18px;
  border-radius: 8px;
  margin: 10px 0;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
}

.profile-form input {
  width: 100%;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
  margin-bottom: 10px;
}

.btn-update {
  background: #27ae60;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

.btn-update i {
  margin-right: 5px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

th {
  background: #f4f6f7;
}

</style>
</head>
<body>

<div class="dashboard">
  <h2><i class="fa-solid fa-user"></i> Welcome, <?= htmlspecialchars($user['fname']); ?></h2>

  <!-- ✅ Message Box -->
  <?php if (!empty($msg)): ?>
    <div class="message-box"><?= $msg; ?></div>
  <?php endif; ?>

  <!-- ✅ Profile Section -->
  <section class="profile-section">
    <h3><i class="fa-solid fa-id-card"></i> Profile Info</h3>
    <form method="POST" class="profile-form">
      <label><i class="fa-solid fa-user-pen"></i> Name:</label>
      <input type="text" name="name" value="<?= htmlspecialchars($user['fname']); ?>" required>

      <label><i class="fa-solid fa-envelope"></i> Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

      <button type="submit" name="update_profile" class="btn-update">
        <i class="fa-solid fa-floppy-disk"></i> Save Changes
      </button>
    </form>
  </section>

  <!-- ✅ Orders Section -->
  <section class="orders-section">
    <h3><i class="fa-solid fa-boxes-stacked"></i> Your Orders</h3>

    <?php if ($orders->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th><i class="fa-solid fa-hashtag"></i> Order ID</th>
            <th><i class="fa-solid fa-money-bill"></i> Amount</th>
            <th><i class="fa-solid fa-clipboard-check"></i> Status</th>
            <th><i class="fa-solid fa-calendar-days"></i> Date</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($order = $orders->fetch_assoc()): ?>
          <tr>
            <td>#<?= htmlspecialchars($order['id']); ?></td>
            <td><i class="fa-solid fa-indian-rupee-sign"></i> <?= htmlspecialchars($order['amount']); ?></td>
            <td><?= htmlspecialchars($order['status']); ?></td>
            <td><?= htmlspecialchars($order['created_at']); ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p><i class="fa-solid fa-circle-exclamation"></i> No orders found.</p>
    <?php endif; ?>
  </section>
</div>

<?php include "footer.php"; ?>

</body>
</html>