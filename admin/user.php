<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

$sql = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Users</title>

</head>

<body>
  <br>
  <h2>Users</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>F_Name</th>
      <th>L_Name</th>
      <th>Email</th>
      <th>Term</th>
      <th>Created At</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['term']) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='6'>No subscribers found</td></tr>";
    }
    ?>
  </table>

</body>

</html>