<?php
session_start();
include("config/config.php");

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
  // CSRF token check
  if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $message = "Invalid CSRF token!";
    $message_type = "error";
  } else {
    // Sanitize and validate input
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['cpassword'] ?? '';
    $term = isset($_POST['term']) ? 1 : 0;

    // Validation
    if (empty($fname) || empty($lname) || empty($email) || empty($password) || empty($confirm_password)) {
      $message = "All fields are required.";
      $message_type = "warning";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid email format.";
      $message_type = "warning";
    } elseif ($password !== $confirm_password) {
      $message = "Passwords do not match.";
      $message_type = "error";
    } elseif (!$term) {
      $message = "You must accept the terms and conditions.";
      $message_type = "info";
    } else {
      // Check if email exists using prepared statement
      $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        $message = "Email is already registered.";
        $message_type = "error";
      } else {
        // Insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO users (fname, lname, email, password, term) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssi", $fname, $lname, $email, $hashed_password, $term);

        if ($insert_stmt->execute()) {
          // Regenerate session ID after registration
          session_regenerate_id(true);
          $_SESSION['user_id'] = $insert_stmt->insert_id;
          $_SESSION['fname'] = $fname;
          $_SESSION['lname'] = $lname;
          $_SESSION['email'] = $email;

          $message = "Registration successful! Welcome, " . htmlspecialchars($fname) . ".";
          $message_type = "success";

          header("Location: index.php");
          exit;
        } else {
          $message = "Database error. Please try again later.";
          $message_type = "error";
        }

        $insert_stmt->close();
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
  <title>rarewiing - Register</title>
  <link rel="icon" type="image/x-icon" href="images/logo/2-01.png">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    crossorigin="anonymous" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000&family=Open+Sans:ital,wght@0,300..800&display=swap"
    rel="stylesheet">

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
      <h2>Create Account</h2>
      <form action="" method="post" enctype="multipart/form-data">

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <!-- First Name -->
        <div class="input-group">
          <label for="fname"><i class="fa-solid fa-user"></i> First Name</label>
          <input type="text" id="fname" name="fname" placeholder="Enter your first name" required>
        </div>

        <!-- Last Name -->
        <div class="input-group">
          <label for="lname"><i class="fa-solid fa-user"></i> Last Name</label>
          <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>
        </div>

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

        <!-- Confirm Password -->
        <div class="input-group" style="position: relative;">
          <label for="cpassword"><i class="fa-solid fa-lock"></i> Confirm Password</label>
          <input type="password" id="confirmPasswordField" name="cpassword" placeholder="Confirm your password" required
            style="padding-right:30px;">
          <span id="toggleConfirmPassword" style="position:absolute; right:10px; top:37px; cursor:pointer;">
            <i class="fa-regular fa-eye"></i>
          </span>
        </div>

        <!-- Terms -->
        <div class="term">
          <input type="checkbox" id="term" name="term" value="1" required>
          <label for="term"> I accept the <a href="terms&condition.php">Terms & Conditions</a></label>
        </div>

        <button type="submit" class="btn-register" name="submit">Register Now</button>

        <div class="guest-access">
          <a href="guest.php"><i class="fa-solid fa-user-secret"></i> Continue as Guest</a>
        </div>
      </form>
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