<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscribed Users</title>
   
</head>
<body>
      <br>
    <h2>Subscribed Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Order Id</th>
            <th>Payment Id</th>
            <th>Signature</th>
            <th>Coustomer Name</th>
            <th>Coustomer Email</th>
            <th>Amount</th>
            <th>Status</th>
            <th>created At</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['payment_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['signature']) . "</td>";
                echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['customer_email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['amount']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No Oredr</td></tr>";
        }
        ?>
    </table>

</body>
</html>