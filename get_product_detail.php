<script src="js/product.js"></script>
<script>
  function showAlert() {
    document.getElementById("alertOverlay").style.display = "flex";
  }

  function closeAlert() {
    document.getElementById("alertOverlay").style.display = "none";
  }   
</script>

<?php
include("config/config.php");

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
          <img id="modalMainImg" src="admin/uploads/<?= $row['front_image'] ?>" class="img-fluid mb-3 border">
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <img src="admin/uploads/<?= $row['front_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
            onclick="document.getElementById('modalMainImg').src=this.src">
          <img src="admin/uploads/<?= $row['back_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
            onclick="document.getElementById('modalMainImg').src=this.src">
          <?php if (!empty($row['side_image'])) { ?>
            <img src="admin/uploads/<?= $row['side_image'] ?>" class="img-thumbnail" style="width:70px;cursor:pointer;"
              onclick="document.getElementById('modalMainImg').src=this.src">
          <?php } ?>
        </div>

        <p><b>Season:</b> <?= htmlspecialchars($row['season']) ?></p>
        <p><b>SKU:</b> <?= htmlspecialchars($row['sku_id']) ?></p>
        <button onclick="showAlert()" class="btn btn-dark flex-fill"><i class="fa-solid fa-wand-magic-sparkles"></i> <b>Care
            for Your Rare Wiing</b></button>

        <!-- Custom alert popup for washing instraction-->
        <div class="overlay" id="alertOverlay">
          <div class="alert-box">
            <!--This is a washing message-->
            <div class="info-box" id="infoBox">
              <!--<p><i class="fa-solid fa-hand-sparkles"></i><b>Care for Your Rare Wiing</b></p>--->
          <p><i class="fa-solid fa-wand-magic-sparkles"></i> <b>Care for Your Rare Wiing</b></p>
          To keep your Rare Wiing pieces soft, durable, and looking new:
          <ul>
            <li><i class="fa-solid fa-snowflake" style="color: #74C0FC;"></i> <b>Wash cold:</b> Hand wash or machine
              wash on gentle cycle with cold water.</li>
            <li><i class="fa-solid fa-jug-detergent" style="color: #f278b3;"></i> <b>Mild detergent only:</b> Avoid
              bleach or harsh chemicals.</li>
            <li><i class="fa-solid fa-wind" style="color: #74C0FC;"></i> <b>Air dry:</b> Hang or lay flat to dry. Avoid
              tumble drying.</li>
            <li><i class="fa-solid fa-temperature-low" style="color: #f13304;"></i> <b>Cool iron:</b> If needed, iron
              inside out on low heat.</li>
          </ul>
          <p><i class="fa-solid fa-tree" style="color: green;"></i> Bamboo fabrics are naturally soft and eco-friendly.
            Treat them right, and they'll stay rare for years.
          </p>
        </div>
        <button onclick="closeAlert()">OK</button>
      </div>
    </div>

  </div>
  <!-- Right side -->
      <div class="col-md-6">
        <h1><?= htmlspecialchars($row['title']) ?></h1>
        <p><b>Price:</b> <i class="fa-solid fa-indian-rupee-sign"></i><?= $row['price'] ?></p>
        <!-- Add to Cart Form -->
        <form id="modalAddToCart" action="add_to_cart.php" method="POST">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <input type="hidden" name="product_title" value="<?= htmlspecialchars($row['title']) ?>">
          <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
          <input type="hidden" name="product_image" value="<?= $row['front_image'] ?>">
          <!-- Sizes -->
          <div class="mb-2">
            <b>Sizes:</b><br>
            <?php foreach ($sizes as $size) { ?>
              <label style="border:1px solid #ccc;height:30px;width:39px;line-height:30px;
                                        cursor:pointer;font-size:14px;border-radius:5px;text-align:center;
                                        display:inline-block;margin-right:5px;position:relative;">
                <input type="radio" name="product_size" value="<?= $size ?>" required
                  style="position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;"
                  onclick="document.getElementById('hiddenSize').value=this.value;">
                <span style="font-size:10px !important;"><?= $size ?></span>
              </label>
            <?php } ?>
          </div>

          <!-- Colors -->
          <div class="mb-2">
            <b>Colors:</b><br>
            <?php foreach ($colors as $color) { ?>
              <label style="border:1px solid #ccc;height:30px;width:69px;line-height:30px;
                                        cursor:pointer;font-size:14px;border-radius:5px;text-align:center;
                                        display:inline-block;margin-right:5px;position:relative;">
                <input type="radio" name="product_color" value="<?= $color ?>" required
                  style="position:absolute;opacity:0;width:100%;height:100%;cursor:pointer;"
                  onclick="document.getElementById('hiddenColor').value=this.value;">
                <span style="font-size:10px !important;"><?= $color ?></span>
              </label>
            <?php } ?>
          </div>

          <style>
            /* ✅ highlight when selected */
            input[type="radio"]:checked+span {
              background-color: #d1f7d6;
              padding: 3.8px;
              border-radius: 3px;
              color: #000;
            }

            /* Overlay (background dim) */
            .overlay {
              position: fixed;
              top: 0;
              left: 0;
              width: 100vw;
              height: 100vh;
              background: rgba(0, 0, 0, 0.6);
              display: none;
              /* hidden by default */
              justify-content: center;
              align-items: center;
              z-index: 9999;
              padding: 20px;
              /* for small screens */
              box-sizing: border-box;
            }

            /* Alert Box (popup content) */
            .alert-box {
              background: #fff;
              padding: 25px 30px;
              border-radius: 12px;
              text-align: left;
              box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
              max-width: 600px;
              width: 100%;
              overflow-y: auto;
              max-height: 90vh;
              animation: fadeIn 0.3s ease-out;
            }

            /* Info Box content inside alert */
            .info-box {
              background: #f9f9f9;
              border: 1px solid #ddd;
              border-radius: 8px;
              padding: 15px 20px;
              margin-bottom: 15px;
            }

            .info-box p {
              margin: 10px 0;
              line-height: 1.5;
            }

            .info-box ul {
              list-style: disc inside;
              margin: 10px 0;
              padding-left: 20px;
            }

            .info-box ul li {
              margin-bottom: 8px;
            }

            /* Highlight icons in list */
            .info-box ul li i {
              margin-right: 8px;
            }

            /* Button */
            .alert-box button {
              display: inline-block;
              margin-top: 10px;
              padding: 10px 20px;
              border: none;
              border-radius: 6px;
              background: #000;
              color: #fff;
              font-weight: 600;
              cursor: pointer;
              transition: 0.2s;
            }

            .alert-box button:hover {
              background: #333;
            }

            /* Fade in animation */
            @keyframes fadeIn {
              from {
                transform: translateY(-20px);
                opacity: 0;
              }

              to {
                transform: translateY(0);
                opacity: 1;
              }
            }

            /* Responsive adjustments */
            @media (max-width: 480px) {
              .alert-box {
                padding: 20px;
                font-size: 14px;
              }

              .info-box {
                padding: 10px;
              }

              .alert-box button {
                width: 100%;
                text-align: center;
              }
            }
          </style>

          <div class="mb-2">
            <label><b>Quantity:</b></label>
            <input type="number" name="product_quantity" value="1" min="1" class="form-control w-25">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-dark flex-fill" name="add_to_cart">
              <i class="fa-solid fa-cart-shopping"></i> Add to Cart
            </button>
        </form>

        <form class="wishlistForm" method="POST" action="wishlist.php">
          <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
          <input type="hidden" name="product_title" value="<?= htmlspecialchars($row['title']) ?>">
          <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
          <input type="hidden" name="product_image" value="<?= $row['front_image'] ?>">
          <input type="hidden" name="product_size" id="hiddenSize">
          <input type="hidden" name="product_color" id="hiddenColor">
          <input type="hidden" name="product_main_color" value="<?= $row['main_color'] ?>">
          <input type="hidden" name="product_qty" value="1">

          <button type="submit" class="btn btn-dark flex-fill" name="add_to_wishlist">
            <i class="fa-regular fa-heart"></i>
          </button>
      </div>
      </form>

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