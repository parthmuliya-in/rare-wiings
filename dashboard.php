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
$stmt_orders = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$orders = $stmt_orders->get_result();
$stmt_orders->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>My Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/about.css">
    <!--font awesome link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--google fonts link-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
</head>

<body>
  <h2>Welcome, <?= htmlspecialchars($user['fname']) ?> 👋</h2>
  <div class="container dashboard">

    <?php if (!empty($msg)): ?>
      <div class="msg"><?= $msg ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- Profile Section -->
      <div class="col-12 col-lg-5">
        <div class="card">
          <div class="card-header"><i class="fa-regular fa-address-card"></i> My Profile</div>
          <div class="card-body">
            <form method="POST">
              <div class="mb-3">
                <label><i class="fa-solid fa-user"></i> Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['fname']) ?>"
                  required>
              </div>
              <div class="mb-3">
                <label><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                  required>
              </div>
              <button type="submit" name="update_profile" class="btn btn-dark w-100">Update Profile</button>
              <!-- Extra Links -->
              <div class="extra-links">
                <a href="forgot_password.php">Forgot Password?</a>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Orders Section -->
      <div class="col-lg-7">
        <div class="card">
          <div class="card-header"><i class="fa-regular fa-bookmark"></i> My Orders</div>
          <div class="card-body table-responsive">
            <?php if ($orders->num_rows > 0): ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Order Id</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Contact</th>
                    <th>Address</th>
                  </tr>
                </thead>
                <tbody>
                          <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                      <td data-label="Order Id"><?= htmlspecialchars($order['id']); ?></td>
                      <td data-label="Status"><span class="badge bg-info"><?= htmlspecialchars($order['status']) ?></span>
                      </td>
                      <td data-label="Total"><i class="fa-solid fa-indian-rupee-sign"></i><?= number_format($order['amount'], 2) ?></td>
                      <td data-label="Date"><?= date("d M Y", strtotime($order['created_at'])) ?></td>
                      <td data-label="Contact"><?= htmlspecialchars($order['customer_phone']) ?></td>
                      <td data-label="Address"><?= htmlspecialchars($order['address']) ?></td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p>No orders found.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include("footer.php"); ?>
</body>

</html>