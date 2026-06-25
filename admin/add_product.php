<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$uploadDir = "uploads/";
$allowedTypes = ['image/jpeg', 'image/png'];

if (isset($_POST['add_product'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $price = $_POST['price'];
    $available_sizes = mysqli_real_escape_string($conn, $_POST['available_sizes']);
    $color_options = mysqli_real_escape_string($conn, $_POST['color_options']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $season = mysqli_real_escape_string($conn, $_POST['season']);
    $sku_id = mysqli_real_escape_string($conn, $_POST['sku_id']);
    $color_way = mysqli_real_escape_string($conn, $_POST['color_way']);
    $main_color = mysqli_real_escape_string($conn, $_POST['main_color']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $out_stock = isset($_POST['out_stock']) ? 1 : 0;

    // Handle image uploads
    $front = $side = $back = "";

    foreach (['front_image', 'side_image', 'back_image'] as $imgField) {
        if (!empty($_FILES[$imgField]['name'])) {
            $fileType = $_FILES[$imgField]['type'];
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['msg'] = "<i class='fa-solid fa-triangle-exclamation'></i> Only JPG or PNG files allowed for $imgField.";
                header("Location: add_product.php");
                exit;
            }

            $fileName = "_" . $imgField . "_" . time() . "_" . basename($_FILES[$imgField]['name']);
            move_uploaded_file($_FILES[$imgField]['tmp_name'], $uploadDir . $fileName);
            $$imgField = $fileName; // variable variables: $front/$side/$back
        }
    }

    // Insert into DB using prepared statement
    $stmt = $conn->prepare("INSERT INTO products (title, price, available_sizes, color_options, description, season, sku_id, color_way, main_color, category, gender, out_stock, front_image, side_image, back_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssssssssissss", $title, $price, $available_sizes, $color_options, $description, $season, $sku_id, $color_way, $main_color, $category, $gender, $out_stock, $front, $side, $back);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "<i class='fa-solid fa-circle-check'></i> Product added successfully!";
    } else {
        $_SESSION['msg'] = "<i class='fa-solid fa-xmark'></i> Error: " . $stmt->error;
    }

    header("Location: add_product.php");
    exit;
}
?>




<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
</head>

<body>
    <div class="form-container">
        <h2>Add New Product</h2>
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="message-box"><?= $_SESSION['msg'];
            unset($_SESSION['msg']); ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" required>
            </div>

            <div class="form-group">
                <label>Available Sizes (comma separated)</label>
                <input type="text" name="available_sizes">
            </div>

            <div class="form-group">
                <label>Color Options (comma separated)</label>
                <input type="text" name="color_options">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label>Season</label>
                <input type="text" name="season">
            </div>

            <div class="form-group">
                <label>SKU ID</label>
                <input type="text" name="sku_id">
            </div>

            <div class="form-group">
                <label>Color Way</label>
                <input type="text" name="color_way">
            </div>

            <div class="form-group">
                <label>Main Color</label>
                <input type="text" name="main_color">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">-- Select --</option>
                    <option value="Hoodies">Hoodies</option>
                    <option value="Premium collection">Premium collection</option>
                    <option value="Sweatshirts">Sweatshirts</option>
                    <option value="Tshirts">Tshirts</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender">
                    <option value="">-- Select --</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                </select>
            </div>

            <div class="form-group">
                <label>Out of Stock <input type="checkbox" name="out_stock"></label>
            </div>

            <div class="form-group">
                <label>Front Image (JPG/PNG)</label>
                <input type="file" name="front_image" accept="image/png, image/jpeg">
            </div>

            <div class="form-group">
                <label>Side Image (JPG/PNG)</label>
                <input type="file" name="side_image" accept="image/png, image/jpeg">
            </div>

            <div class="form-group">
                <label>Back Image (JPG/PNG)</label>
                <input type="file" name="back_image" accept="image/png, image/jpeg">
            </div>

            <button type="submit" name="add_product" class="btn"><i class="fa-solid fa-circle-plus"></i> Add
                Product</button>
        </form>
    </div>
</body>

</html>
<?php $conn->close(); ?>