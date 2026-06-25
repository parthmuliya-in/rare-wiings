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

// ===== DELETE PRODUCT =====
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $res = mysqli_query($conn, "SELECT front_image, side_image, back_image FROM products WHERE id=$id");
  if ($row = mysqli_fetch_assoc($res)) {
    foreach (['front_image', 'side_image', 'back_image'] as $img) {
      if (!empty($row[$img]) && file_exists($uploadDir . $row[$img])) {
        unlink($uploadDir . $row[$img]);
      }
    }
  }
  mysqli_query($conn, "DELETE FROM products WHERE id=$id");
  $_SESSION['msg'] = "<i class='fa-solid fa-circle-check'></i> Product deleted successfully!";
  header("Location: manage_products.php");
  exit;
}

// ===== UPDATE PRODUCT =====
if (isset($_POST['update_product'])) {
  $id = (int) $_POST['id'];
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $price = floatval($_POST['price']);
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

  // Fetch old images
  $res = mysqli_query($conn, "SELECT front_image, side_image, back_image FROM products WHERE id=$id");
  $old = mysqli_fetch_assoc($res);

  // Function to handle image upload
  function upload_image($fileField, $oldFile = "")
  {
    global $uploadDir, $allowedTypes;
    if (!empty($_FILES[$fileField]['name'])) {
      $fileType = $_FILES[$fileField]['type'];
      if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['msg'] = "<i class='fa-solid fa-triangle-exclamation'></i> Only JPG/PNG allowed for $fileField.";
        header("Location: manage_products.php");
        exit;
      }
      // Delete old file
      if (!empty($oldFile) && file_exists($uploadDir . $oldFile))
        unlink($uploadDir . $oldFile);
      $fileName = "_" . $fileField . "_" . time() . "_" . basename($_FILES[$fileField]['name']);
      move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadDir . $fileName);
      return $fileName;
    }
    return $oldFile;
  }

  $front = upload_image('front_image', $old['front_image']);
  $side = upload_image('side_image', $old['side_image']);
  $back = upload_image('back_image', $old['back_image']);

  // Update product using prepared statement
  $stmt = $conn->prepare("UPDATE products SET title=?, price=?, available_sizes=?, color_options=?, description=?, season=?, sku_id=?, color_way=?, main_color=?, category=?, gender=?, out_stock=?, front_image=?, side_image=?, back_image=? WHERE id=?");
  $stmt->bind_param("sdssssssssissssi", $title, $price, $available_sizes, $color_options, $description, $season, $sku_id, $color_way, $main_color, $category, $gender, $out_stock, $front, $side, $back, $id);
  $stmt->execute();

  $_SESSION['msg'] = "<i class='fa-solid fa-circle-check'></i> Product updated successfully!";
  header("Location: manage_products.php");
  exit;
}

// ===== FETCH PRODUCTS =====
$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <h2><i class="fa-solid fa-box-open"></i> Manage Products</h2>

  <!-- ✅ Show Message Box -->
  <?php if (isset($_SESSION['msg'])): ?>
    <div class="alert success">
      <?= $_SESSION['msg'] ?>
    </div>
    <?php unset($_SESSION['msg']); ?>
  <?php endif; ?>

  <a href="add_product.php" class="btn"><i class="fa-solid fa-circle-plus"></i> Add Product</a>

  <table>
    <tr>
      <th>ID</th>
      <th>Title</th>
      <th>Price</th>
      <th>Images</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><i class="fa-solid fa-indian-rupee-sign"></i><?= $row['price'] ?></td>
        <td>
          <?php foreach (['front_image', 'side_image', 'back_image'] as $img): ?>
            <?php if (!empty($row[$img])): ?>
              <img src="uploads/<?= htmlspecialchars($row[$img]) ?>" width="60" height="60" loading="lazy">
            <?php endif; ?>
          <?php endforeach; ?>
        </td>
        <td>
          <button onclick="document.getElementById('edit-<?= $row['id'] ?>').style.display='block'"
            class="btn">Edit</button>
          <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete product?')" class="btn">Delete</a>
        </td>
      </tr>

      <!-- Edit Form -->
      <tr id="edit-<?= $row['id'] ?>" style="display:none;">
        <td colspan="5">
          <form method="POST" enctype="multipart/form-data" class="edit-form">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <label>Title:</label><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>"><br>
            <label>Price:</label><input type="number" step="0.01" name="price" value="<?= $row['price'] ?>"><br>
            <label>Sizes:</label><input type="text" name="available_sizes"
              value="<?= htmlspecialchars($row['available_sizes']) ?>"><br>
            <label>Colors:</label><input type="text" name="color_options"
              value="<?= htmlspecialchars($row['color_options']) ?>"><br>
            <label>Description:</label><textarea
              name="description"><?= htmlspecialchars($row['description']) ?></textarea><br>
            <label>Season:</label><input type="text" name="season" value="<?= htmlspecialchars($row['season']) ?>"><br>
            <label>SKU ID:</label><input type="text" name="sku_id" value="<?= htmlspecialchars($row['sku_id']) ?>"><br>
            <label>Colorway:</label><input type="text" name="color_way"
              value="<?= htmlspecialchars($row['color_way']) ?>"><br>
            <label>Main Color:</label><input type="text" name="main_color"
              value="<?= htmlspecialchars($row['main_color']) ?>"><br>
            <label>Category:</label>
            <select name="category">
              <option value="<?= htmlspecialchars($row['category']) ?>"><?= htmlspecialchars($row['category']) ?></option>
              <option value="Hoodies">Hoodies</option>
              <option value="Premium collection">Premium collection</option>
              <option value="Sweatshirts">Sweatshirts</option>
              <option value="Tshirts">Tshirts</option>
            </select><br>
            <label>Gender:</label>
            <select name="gender">
              <option value="<?= htmlspecialchars($row['gender']) ?>"><?= htmlspecialchars($row['gender']) ?></option>
              <option value="men">Men</option>
              <option value="women">Women</option>
            </select><br>
            <label>Out of Stock:</label><input type="checkbox" name="out_stock" <?= $row['out_stock'] ? "checked" : "" ?>><br>
            <label>Front Image (JPG/PNG):</label><input type="file" name="front_image" accept="image/png,image/jpeg"><br>
            <label>Side Image (JPG/PNG):</label><input type="file" name="side_image" accept="image/png,image/jpeg"><br>
            <label>Back Image (JPG/PNG):</label><input type="file" name="back_image" accept="image/png,image/jpeg"><br>
            <button type="submit" name="update_product" class="btn"><i class="fa-solid fa-floppy-disk"></i> Save
              Changes</button>
          </form>
        </td>
      </tr>

    <?php endwhile; ?>
  </table>


</body>

</html>