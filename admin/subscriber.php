<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

$sql = "SELECT id, first_name, last_name, email, terms, created_at FROM subscribe ORDER BY id DESC";
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
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Terms</th>
            <th>Created At</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . ($row['terms'] ? "<i class='fa-solid fa-check' style='color: #5be539ff;'></i> Agreed" : "<i class='fa-solid fa-xmark' style='color: red;'></i> Not Agreed") . "</td>";
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