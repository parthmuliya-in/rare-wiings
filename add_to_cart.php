<?php
session_start();
include("config/config.php");

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $title = $_POST['product_title'];
    $price = $_POST['product_price'];
    $image = $_POST['product_image'];
    $size = $_POST['product_size'];
    $color = $_POST['product_color'];
    $main_color= $_POST['product_main_color'];
    $quantity = $_POST['product_quantity'];

    $cart_item = [
        "id" => $id,
        "title" => $title,
        "price" => $price,
        "image" => $image,
        "size" => $size,
        "color" => $color,
        "main_color"=>$main_color,        
        "quantity" => $quantity
    ];

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $id && $item['size'] == $size && $item['color'] == $color) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = $cart_item;
        }
        $_SESSION['cart'] = $cart;
    } else {
        $_SESSION['cart'] = [$cart_item];
    }

    // Redirect back to product page
    header("Location: product.php");
    exit;
}
?>