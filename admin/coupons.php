<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$message = "";

// ===== ADD COUPON =====
if (isset($_POST['add_coupon'])) {
    $code = trim($_POST['code']);
    $discount = (int)$_POST['discount'];
    $expiry = $_POST['expiry'];
    $status = isset($_POST['status']) ? 1 : 0;

    if ($stmt = $conn->prepare("INSERT INTO coupons (code, discount_percent, expiry_date, status) VALUES (?, ?, ?, ?)")) {
        $stmt->bind_param("sisi", $code, $discount, $expiry, $status);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "<i class='fa-solid fa-circle-check'></i> Coupon added successfully!";
        } else {
            $_SESSION['msg'] = "<i class='fa-solid fa-triangle-exclamation'></i> Error adding coupon!";
        }
        $stmt->close();
        header("Location: coupons.php");
        exit;
    }
}

// ===== DELETE COUPON =====
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['msg'] = "<i class='fa-solid fa-trash-can'></i> Coupon deleted successfully!";
    header("Location: coupons.php");
    exit;
}

// ===== UPDATE COUPON =====
if (isset($_POST['update_coupon'])) {
    $id = (int)$_POST['id'];
    $code = trim($_POST['code']);
    $discount = (int)$_POST['discount'];
    $expiry = $_POST['expiry'];
    $status = isset($_POST['status']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE coupons SET code=?, discount_percent=?, expiry_date=?, status=? WHERE id=?");
    $stmt->bind_param("sisii", $code, $discount, $expiry, $status, $id);
    if ($stmt->execute()) {
        $_SESSION['msg'] = "<i class='fa-solid fa-pen-to-square'></i> Coupon updated successfully!";
    } else {
        $_SESSION['msg'] = "<i class='fa-solid fa-bug'></i> Error updating coupon!";
    }
    $stmt->close();
    header("Location: coupons.php");
    exit;
}

// ===== FETCH COUPONS =====
$result = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Coupon Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  
</head>

<body>

  <h2> Coupon Management</h2>

  <!-- ✅ Show Message Box -->
  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert success">
      <?= $_SESSION['msg'] ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <!-- Add Coupon Form -->
  <form method="POST">
    <input type="text" name="code" placeholder="Coupon Code" required>
    <input type="number" name="discount" placeholder="Discount %" required>
    <input type="date" name="expiry" required>
    <label><input type="checkbox" name="status" checked> Active</label>
    <button type="submit" name="add_coupon">
      <i class="fa-solid fa-circle-plus"></i> Add Coupon
    </button>
  </form>

  <!-- Coupons Table -->
  <table>
    <thead>
      <tr>
        <th><i class="fa-solid fa-id-badge"></i> ID</th>
        <th><i class="fa-solid fa-code"></i> Code</th>
        <th><i class="fa-solid fa-percent"></i> Discount</th>
        <th><i class="fa-solid fa-calendar-day"></i> Expiry</th>
        <th><i class="fa-solid fa-toggle-on"></i> Status</th>
        <th><i class="fa-solid fa-gear"></i> Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form method="POST">
            <td data-label="ID">
              <?= $row['id'] ?>
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
            </td>

            <td data-label="Code">
              <input type="text" name="code" value="<?= htmlspecialchars($row['code']) ?>" required>
            </td>

            <td data-label="Discount">
              <input type="number" name="discount" value="<?= $row['discount_percent'] ?>" required>
            </td>

            <td data-label="Expiry">
              <input type="date" name="expiry" value="<?= $row['expiry_date'] ?>" required>
            </td>

            <td data-label="Status">
              <label><input type="checkbox" name="status" <?= $row['status'] ? "checked" : "" ?>> Active</label>
            </td>

            <td data-label="Actions">
              <button type="submit" name="update_coupon" title="Update Coupon">
                <i class="fa-solid fa-pen-to-square"></i> Update
              </button>
              <a href="coupons.php?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?');">
                <i class="fa-solid fa-trash"></i> Delete
              </a>
            </td>
          </form>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
</html>

<?php $conn->close(); ?>
