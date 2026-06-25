<?php
// user name=admin password=Admin@123

// ---------- CONFIG ----------
session_start(); // MUST be called before using $_SESSION
include("../config/config.php");
// ---------- Security settings ----------
$MAX_ATTEMPTS = 5;        // lock after this many failed attempts
$LOCKOUT_MINUTES = 15;    // lockout duration

// Initialize attempt tracking
if (!isset($_SESSION['login_attempts'])) {
  $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['lockout_until'])) {
  $_SESSION['lockout_until'] = 0;
}

// ---------- CSRF token ----------
if (empty($_SESSION['csrf_token'])) {
  // random_bytes is cryptographically secure
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// If already logged in, redirect to admin area
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  header("Location: index.php");
  exit;
}

// ---------- Handle POST (login attempt) ----------
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Check lockout
  $now = time();
  if (!empty($_SESSION['lockout_until']) && $now < $_SESSION['lockout_until']) {
    $remaining = ceil(($_SESSION['lockout_until'] - $now) / 60);
    $errors[] = "Too many failed attempts. Try again in {$remaining} minute(s).";
  } else {
    // Validate CSRF token
    $posted_csrf = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $posted_csrf)) {
      $errors[] = "Invalid request (CSRF token mismatch).";
    } else {
      // sanitize username and read password
      $username = trim($_POST['username'] ?? '');
      $password = $_POST['password'] ?? '';

      if ($username === '' || $password === '') {
        $errors[] = "Username and password are required.";
      } else {
        // Prepared statement to get user
        $sql = "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
          $errors[] = "Database error (prepare failed).";
        } else {
          mysqli_stmt_bind_param($stmt, "s", $username);
          mysqli_stmt_execute($stmt);

          // Use bind_result & fetch to be compatible with all setups
          mysqli_stmt_bind_result($stmt, $db_id, $db_username, $db_hashed_password);
          $found = mysqli_stmt_fetch($stmt);
          mysqli_stmt_close($stmt);

          if (!$found) {
            // no such user
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= $MAX_ATTEMPTS) {
              $_SESSION['lockout_until'] = time() + ($LOCKOUT_MINUTES * 60);
            }
            $errors[] = "Invalid username or password.";
          } else {
            // verify password
            if (password_verify($password, $db_hashed_password)) {
              // Successful login
              session_regenerate_id(true); // prevent session fixation
              $_SESSION['admin_logged_in'] = true;
              $_SESSION['admin_id'] = $db_id;
              $_SESSION['admin_username'] = $db_username;

              // reset login attempts
              $_SESSION['login_attempts'] = 0;
              $_SESSION['lockout_until'] = 0;

              // optional: regenerate CSRF token for next operations
              $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

              header("Location: index.php");
              exit;
            } else {
              // wrong password
              $_SESSION['login_attempts']++;
              if ($_SESSION['login_attempts'] >= $MAX_ATTEMPTS) {
                $_SESSION['lockout_until'] = time() + ($LOCKOUT_MINUTES * 60);
              }
              $errors[] = "Invalid username or password.";
            }
          }
        }
      }
    }
  }
}

// ---------- HTML OUTPUT ----------
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/chatbot.css">
  <link rel="icon" type="image/x-icon" href="../images/logo/2-01.png">
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

<body class="admin-login-body">

  <div class="admin-login-box">
    <h4 class="admin-login-title">Admin Login</h4>

    <?php if (!empty($errors)): ?>
      <div class="admin-error-list">
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" novalidate class="admin-login-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

      <div>
        <label class="admin-form-label">Username</label>
        <input name="username" type="text" class="admin-form-input" required
          value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
      </div>

      <div style="position: relative;">
        <label class="admin-form-label">Password</label>
        <input id="passwordField" name="password" type="password" class="admin-form-input" required
          style="padding-right: 30px;">

        <!-- Eye icon -->
        <span id="togglePassword"
          style="position: absolute; right: 10px; top: 43px; cursor: pointer; user-select: none;">
          <i class="fa-regular fa-eye"></i>
        </span>
      </div>

      <button type="submit" class="admin-login-btn">Login</button>
    </form>

    <div class="admin-login-info">
      <?php
      if (!empty($_SESSION['lockout_until']) && time() < $_SESSION['lockout_until']) {
        $mins = ceil(($_SESSION['lockout_until'] - time()) / 60);
        echo "Too many failed attempts. Try again in {$mins} minute(s).";
      } else {
        echo "Failed attempts: " . intval($_SESSION['login_attempts']) . " / {$MAX_ATTEMPTS}";
      }
      ?>
    </div>
  </div>
 <script src="../js/main.js"></script>
</body>

</html>