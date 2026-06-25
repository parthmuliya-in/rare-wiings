<?php
session_start();
include("config/config.php");

// --- RATE LIMITING (Prevent abuse) ---
$ip = $_SERVER['REMOTE_ADDR'];
$time_now = time();
$limit_window = 60; // 1 minute window
$max_attempts = 5;

if (!isset($_SESSION['reset_attempts'])) {
    $_SESSION['reset_attempts'] = [];
}

// remove old attempts older than 60 seconds
$_SESSION['reset_attempts'] = array_filter($_SESSION['reset_attempts'], function ($t) use ($time_now, $limit_window) {
    return ($time_now - $t) < $limit_window;
});

// block if too many attempts
if (count($_SESSION['reset_attempts']) >= $max_attempts) {
    $message = "<div class='message-box'><i class='fa-solid fa-ban'></i> Too many attempts! Try again after 1 minute.</div>";
} elseif (isset($_POST['forgot'])) {
    $_SESSION['reset_attempts'][] = $time_now;

    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);

    if (empty($email) || empty($new_password)) {
        $message = "<div class='message-box'><i class='fa-solid fa-circle-exclamation'></i> Please fill all fields.</div>";
    }else {
        // Use prepared statement for safety
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
            $update->bind_param("ss", $hashed_password, $email);

            if ($update->execute()) {
                $message = "<div class='msg success'><i class='fa-solid fa-check-circle'></i> Password updated successfully. You can now <a href='login.php'>login</a>.</div>";
            } else {
                $message = "<div class='message-box'><i class='fa-solid fa-times-circle'></i> Error updating password. Try again.</div>";
            }

            $update->close();
        } else {
            $message = "<div class='message-box'><i class='fa-solid fa-user-slash'></i> Email not registered.</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="images/logo/2-01.png">
    <!--css link-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css">
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

    <div class="login-container">
        <div class="login-box">
            <h2>Forgot Password</h2>

           <?php if (isset($message)) echo $message; ?>

            <form method="post">
                <div class="input-group">
                    <label><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label><i class="fa-solid fa-lock"></i> New Password</label>
                    <input type="password" name="new_password" placeholder="Enter New  password" required>
                </div>
                <button type="submit" class="btn-login" name="forgot"><i class="fa-regular fa-user"></i> Login</button>
                <!-- Extra Links -->
                <div class="extra-links">
                    <a href="register.php">Create Account</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>