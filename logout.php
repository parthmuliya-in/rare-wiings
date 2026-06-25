<?php
include("config/config.php");
session_start();

// remove all session variables
session_unset();

// destroy the session
session_destroy();

// clear the PHP session cookie from browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// redirect to login/index
header("Location: index.php");
exit;
?>