<?php
include("config/config.php");
include("header.php");
include("chatbot.php");

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$message = ''; // For message box

// ✅ Add to Wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_wishlist'])) {
    $product_id = intval($_POST['product_id']);
    $product_title = trim($_POST['product_title']);
    $product_price = floatval($_POST['product_price']);
    $product_image = trim($_POST['product_image']);
    $product_main_color = trim($_POST['product_main_color']);
    $product_size = trim($_POST['product_size']);
    $product_color = !empty($_POST['product_color']) ? trim($_POST['product_color']) : $product_main_color;
    $product_quantity = intval($_POST['product_quantity'] ?? 1);

    // Check if item already exists
    $stmt_check = $conn->prepare("SELECT id FROM wishlist WHERE user_id=? AND product_id=?");
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $message = '<i class="fa-solid fa-circle-exclamation" style="color:orange"></i> This item is already in your wishlist!';
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id, product_title, product_price, product_image, size, color, product_quantity, added_at)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt_insert->bind_param("issdsssi", $user_id, $product_id, $product_title, $product_price, $product_image, $product_size, $product_color, $product_quantity);
        if ($stmt_insert->execute()) {
            $message = '<i class="fa-solid fa-circle-check" style="color:green"></i> Item added to wishlist!';
        } else {
            $message = '<i class="fa-solid fa-circle-xmark" style="color:red"></i> Failed to add item. Please try again.';
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}

// ✅ Remove item from wishlist
if (isset($_GET['remove'])) {
    $remove_id = intval($_GET['remove']);
    $stmt_remove = $conn->prepare("DELETE FROM wishlist WHERE id=? AND user_id=?");
    $stmt_remove->bind_param("ii", $remove_id, $user_id);
    if ($stmt_remove->execute()) {
        $message = '<i class="fa-solid fa-circle-check" style="color:green"></i> Item removed from wishlist!';
    } else {
        $message = '<i class="fa-solid fa-circle-xmark" style="color:red"></i> Failed to remove item.';
    }
    $stmt_remove->close();
}

// Fetch wishlist
$stmt_wishlist = $conn->prepare("SELECT * FROM wishlist WHERE user_id=? ORDER BY added_at DESC");
$stmt_wishlist->bind_param("i", $user_id);
$stmt_wishlist->execute();
$result = $stmt_wishlist->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wishlist</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<!-- Message Box -->
<h2>My Wishlist <i class="fa-regular fa-heart"></i></h2>
<div class="wishlist-container">
    <a href="product.php">Add once</a>
    <div id="messageBox"><?= $message ?></div>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="wishlist-item">
            <img src="admin/uploads/<?= htmlspecialchars($row['product_image']) ?>" alt="<?= htmlspecialchars($row['product_title']) ?>">
            <div>
                <h3><?= htmlspecialchars($row['product_title']) ?></h3>
                <p>Price: <i class="fa-solid fa-indian-rupee-sign"></i> <?= $row['product_price'] ?></p>
                <p>Color: <?= htmlspecialchars($row['color']) ?></p>
                <p>Size: <?= htmlspecialchars($row['size']) ?></p>
            </div>

            <div class="wishlist-actions">
                <!-- Move to Cart -->
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                    <input type="hidden" name="product_title" value="<?= htmlspecialchars($row['product_title']) ?>">
                    <input type="hidden" name="product_image" value="<?= $row['product_image'] ?>">
                    <input type="hidden" name="product_price" value="<?= $row['product_price'] ?>">
                    <input type="hidden" name="product_size" value="<?= $row['size'] ?>">
                    <input type="hidden" name="product_color" value="<?= $row['color'] ?>">
                    <input type="hidden" name="product_quantity" value="1">
                    <button type="submit" class="btn-cart" name="add_to_cart"><i class="fa-solid fa-cart-shopping"></i> Move to Cart</button>
                </form>

                <!-- Remove -->
                <a href="wishlist.php?remove=<?= $row['id'] ?>" class="wishlist-remove-btn"><i class="fa-solid fa-xmark"></i></a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;"><i class="fa-solid fa-heart-circle-exclamation"></i> Your wishlist is empty!</p>
    <?php endif; ?>
</div>


<script>
// Show message box if message exists
let msgBox = document.getElementById('messageBox');
if(msgBox.innerHTML.trim() !== '') {
    msgBox.style.display = 'block';
    setTimeout(()=>{ msgBox.style.display = 'none'; }, 3000); // hide after 3 sec
}
</script>

<?php include("footer.php"); ?>
</body>
</html>
