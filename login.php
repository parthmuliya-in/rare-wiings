<?php
session_start();
include("config/config.php");

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
  // CSRF check
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $message = "Invalid CSRF token!";
    $message_type = "error";
  } else {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
      $message = "Both fields are required.";
      $message_type = "warning";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid email format.";
      $message_type = "warning";
    } else {
      // Prepare statement to fetch user
      $stmt = $conn->prepare("SELECT id, fname, lname, email, password FROM users WHERE email = ? LIMIT 1");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fname, $lname, $email_db, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
          // Regenerate session ID
          session_regenerate_id(true);
          $_SESSION['user_id'] = $id;
          $_SESSION['fname'] = $fname;
          $_SESSION['lname'] = $lname;
          $_SESSION['email'] = $email_db;

          $message = "Login successful! Welcome back, " . htmlspecialchars($fname) . ".";
          $message_type = "success";

          header("Location: index.php");
          exit;
        } else {
          $message = "Incorrect password.";
          $message_type = "error";
        }
      } else {
        $message = "No account found with this email.";
        $message_type = "error";
      }

      $stmt->close();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>rarewiing - Login</title>
  <link rel="icon" type="image/x-icon" href="images/logo/2-01.png">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    crossorigin="anonymous" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/form.css">
  <!-- Message box CSS -->
  <link rel="stylesheet" href="css/message-box.css">

  <!-- Message box JS -->
  <script src="js/message-box.js"></script>

</head>

<body>

  <div class="register-container">
    <div class="register-box">
      <h2>Login</h2>
      <form action="" method="post">

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <!-- Email -->
        <div class="input-group">
          <label for="email"><i class="fa-solid fa-envelope"></i> Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>

        <!-- Password -->
        <div class="input-group" style="position: relative;">
          <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
          <input type="password" id="passwordField" name="password" placeholder="Enter your password" required
            style="padding-right:30px;">
          <span id="togglePassword" style="position:absolute; right:10px; top:37px; cursor:pointer;">
            <i class="fa-regular fa-eye"></i>
          </span>
        </div>

        <button type="submit" class="btn-register" name="login">Login</button>
        
        
      </form>
      <div class="extra-links">
        <a href="forgot_password.php" >forgot Password</a>
        <a href="register.php"> Create Account</a>
      </div>
    </div>
  </div>

  <?php if (!empty($message)): ?>
    <script>
      showMessage("<?= htmlspecialchars($message) ?>", "<?= $message_type ?>");
    </script>
  <?php endif; ?>
  <script src="js/main.js"></script>

</body>

</html>