<script>
    // Script for washing instraction
    function showAlert() {
        document.getElementById("alertOverlay").style.display = "flex";
    }

    function closeAlert() {
        document.getElementById("alertOverlay").style.display = "none";
    }   
</script>

<?php
include("../config/config.php");

// Handle AJAX product fetch
if (isset($_POST['ajax']) && $_POST['ajax'] == "getProduct" && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $sizes = array_map('trim', explode(",", $row['available_sizes']));
        $colors = array_map('trim', explode(",", $row['color_options']));
        ?>
        <div class="row">
            <!-- Left side -->
            <div class="col-md-6">
                <div class="img-magnifier-container">
                    <img id="modalMainImg" src="uploads/<?= $row['front_image'] ?>" class="img-fluid mb-3 border">
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <img src="uploads/<?= $row['front_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
                        onclick="document.getElementById('modalMainImg').src=this.src">
                    <img src="uploads/<?= $row['back_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
                        onclick="document.getElementById('modalMainImg').src=this.src">
                    <?php if (!empty($row['side_image'])) { ?>
                        <img src="uploads/<?= $row['side_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
                            onclick="document.getElementById('modalMainImg').src=this.src">
                    <?php } ?>
                </div>

                <p><b>Season:</b> <?= htmlspecialchars($row['season']) ?></p>
                <p><b>SKU:</b> <?= htmlspecialchars($row['sku_id']) ?></p>


            </div>
            <!-- Right side -->
            <div class="col-md-6">
                <h1><?= htmlspecialchars($row['title']) ?></h1>
                <p><b>Price:</b> ₹<?= $row['price'] ?></p>
                <!-- Add to Cart Form -->
                <div class="mb-2">
                    <b>Sizes:</b><br>
                    <?php foreach ($sizes as $size) { ?>
                        <label style="border:1px solid #ccc;height:30px;width:39px;line-height:30px;
                                        cursor:pointer;font-size:14px;border-radius:5px;text-align:center;
                                        display:inline-block;margin-right:5px;position:relative;">
                            <span style="font-size:10px !important;"><?= $size ?></span>
                        </label>
                    <?php } ?>
                </div>

                <!-- Colors -->
                <div class="mb-2">
                    <b>Colors:</b><br>
                    <?php foreach ($colors as $color) { ?>
                        <label style="border:1px solid #ccc;height:30px;width:70px;line-height:30px;
                                        cursor:pointer;font-size:14px;border-radius:5px;text-align:center;
                                        display:inline-block;margin-right:5px;position:relative;">

                            <span style="font-size:10px !important;"><?= $color ?></span>
                        </label>
                    <?php } ?>
                    <p><b>Category:</b> <?= $row['category'] ?></p>
                    <p><b>Gender:</b> <?= $row['gender'] ?></p>
                    <p><b>Created:</b><?= $row['created_at'] ?></p>
                </div>
            </div>

            <hr>
            <p><b>Details:</b></p><br>
            <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                <?php
                $items = explode("\n", $row['description']);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (!empty($item)) {
                        echo "<li>" . htmlspecialchars($item) . "</li>";
                    }
                }
                ?>
            </ul>
        </div>
        </div>
        <?php
    }
    exit;
}
?>

<script src="../js/product.js"></script>