<?php
session_start();
$_SESSION = [
    'guest' => true,
    'fname' => 'Guest',
    'lname' => '',
    'email' => 'guest@rarewiing.com'
];
header("Location: index.php");
exit;
?>
