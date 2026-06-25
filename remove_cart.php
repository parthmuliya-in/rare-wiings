<?php
session_start();
$index = $_GET['index'] ?? null;
if($index !== null && isset($_SESSION['cart'][$index])){
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
}
header("Location: cart.php");
exit;
