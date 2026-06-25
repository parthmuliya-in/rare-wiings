<?php
include("header.php");
include("chatbot.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>rarewiing</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
  <!--Filter page-->
  <?php
  include("config/config.php"); // DB connection
  
  // Default query
  $query = "SELECT * FROM products WHERE 1=1 ";

  // Price filter
  if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
    $min = (int) $_GET['min_price'];
    $max = (int) $_GET['max_price'];
    $query .= " AND price BETWEEN $min AND $max";
  }

  // Category filter
  if (!empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $query .= " AND category = '$category'";
  }

  
  // ✅ Sizes filter (comma-separated values support)
  if (!empty($_GET['available_sizes'])) {
    $available_sizes = mysqli_real_escape_string($conn, $_GET['available_sizes']);
    $query .= " AND available_sizes LIKE '%$available_sizes%'";
  }
  
  // Gender filter
  if (!empty($_GET['gender'])) {
    $gender = mysqli_real_escape_string($conn, $_GET['gender']);
    $query .= " AND gender = '$gender'";
  }

  // Sorting
  $orderBy = "";
  if (!empty($_GET['sort'])) {
    switch ($_GET['sort']) {
      case "newest":
        $orderBy = " ORDER BY created_at DESC";
        break;
      case "atoz":
        $orderBy = " ORDER BY title ASC";
        break;
      case "ztoa":
        $orderBy = " ORDER BY title DESC";
        break;
      case "lowtohigh":
        $orderBy = " ORDER BY price ASC";
        break;
      case "hightolow":
        $orderBy = " ORDER BY price DESC";
        break;
    }
  }
  $query .= $orderBy;

  $result = mysqli_query($conn, $query);
  ?>

  <script>
    $(function () {
      // Price Range Slider
      $("#slider-range").slider({
        range: true,
        min: 0,
        max: 50000,
        values: [
          <?= isset($_GET['min_price']) ? (int) $_GET['min_price'] : 500 ?>,
          <?= isset($_GET['max_price']) ? (int) $_GET['max_price'] : 3000 ?>
        ],
        slide: function (event, ui) {
          $("#amount").val("₹" + ui.values[0] + " - ₹" + ui.values[1]);
        },
        change: function (event, ui) {
          $("input[name=min_price]").val(ui.values[0]);
          $("input[name=max_price]").val(ui.values[1]);
          $("#filterForm").submit();
        }
      });
      $("#amount").val("₹" + $("#slider-range").slider("values", 0) +
        " - ₹" + $("#slider-range").slider("values", 1));

      // Auto submit dropdowns
      $("#sort, #category, #available_sizes,#gender").change(function () {
        $("#filterForm").submit();
      });
    });
  </script>




  <!-- Filter Section -->
  <div class="filter-box">
    <form method="GET" id="filterForm" style="display:flex; flex-wrap:wrap; gap:20px;">

      <!-- Price Filter -->
      <div class="filter-group">
        <label>Price Range:</label>
        <input type="text" id="amount" readonly>
        <div id="slider-range"></div>
        <input type="hidden" name="min_price" value="<?= $_GET['min_price'] ?? 500 ?>">
        <input type="hidden" name="max_price" value="<?= $_GET['max_price'] ?? 50000 ?>">
      </div>

      <!-- Category Filter -->
      <div class="filter-group">
        <label>Category:</label>
        <select name="category" id="category">
          <option value="">All</option>
          <option value="Hoodies" <?= ($_GET['category'] ?? '') == "Hoodies" ? 'selected' : '' ?>>Hoodies</option>
          <option value="Premium collection" <?= ($_GET['category'] ?? '') == "Premium collection" ? 'selected' : '' ?>>Premium collection</option>
          <option value="Sweatshirts" <?= ($_GET['category'] ?? '') == "Sweatshirts" ? 'selected' : '' ?>>Sweatshirts</option>
          <option value="Tshirts" <?= ($_GET['category'] ?? '') == "Tshirts" ? 'selected' : '' ?>>Tshirts</option>
        </select>
      </div>

      <!-- Size Filter -->
      <div class="filter-group">
        <label>Size:</label>
        <select name="available_sizes" id="available_sizes">
          <option value="">All</option>
          <option value="S" <?= ($_GET['available_sizes'] ?? '') == "S" ? "selected" : "" ?>>S</option>
          <option value="M" <?= ($_GET['available_sizes'] ?? '') == "M" ? "selected" : "" ?>>M</option>
          <option value="L" <?= ($_GET['available_sizes'] ?? '') == "L" ? "selected" : "" ?>>L</option>
          <option value="XL" <?= ($_GET['available_sizes'] ?? '') == "XL" ? "selected" : "" ?>>XL</option>
          <option value="XXL" <?= ($_GET['available_sizes'] ?? '') == "XXL" ? "selected" : "" ?>>XXL</option>
          <option value="X" <?= ($_GET['available_sizes'] ?? '') == "X" ? "selected" : "" ?>>X</option>
        </select>
      </div>

<!-- Gender Filter -->
      <div class="filter-group">
        <label>Gender:</label>
        <select name="gender" id="gender">
          <option value="">All</option>
          <option value="Men" <?= ($_GET['gender'] ?? '') == "Men" ? "selected" : "" ?>>Men</option>
          <option value="Women" <?= ($_GET['gender'] ?? '') == "Women" ? "selected" : "" ?>>Women</option>
        </select>
      </div>


      <!-- Sort Filter (at the end) -->
      <div class="filter-group" style="margin-left:auto;">
        <label>Sort By:</label>
        <select name="sort" id="sort">
          <option value="">-- Select --</option>
          <option value="newest" <?= ($_GET['sort'] ?? '') == "newest" ? "selected" : "" ?>>Recently Added</option>
          <option value="atoz" <?= ($_GET['sort'] ?? '') == "atoz" ? "selected" : "" ?>>A → Z</option>
          <option value="ztoa" <?= ($_GET['sort'] ?? '') == "ztoa" ? "selected" : "" ?>>Z → A</option>
          <option value="lowtohigh" <?= ($_GET['sort'] ?? '') == "lowtohigh" ? "selected" : "" ?>>Price: Low → High
          </option>
          <option value="hightolow" <?= ($_GET['sort'] ?? '') == "hightolow" ? "selected" : "" ?>>Price: High → Low
          </option>
        </select>
      </div>

    </form>
  </div>
  <!--End of Filter-->
  <div class="product-container">
    <?php
    ///$result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
    
   while ($row = mysqli_fetch_assoc($result)) {
    $sizes = array_map('trim', explode(",", $row['available_sizes']));
    $colors = array_map('trim', explode(",", $row['color_options']));
    $isOutOfStock = ($row['out_stock'] == 1); // check if stock is 0
      ?>
      <div class="product-card">
        <img id="mainImage<?= $row['id'] ?>" src="admin/uploads/<?= $row['front_image'] ?>" class="main-img">

        <div class="thumbs">
            <img src="admin/uploads/<?= $row['front_image'] ?>" onclick="changeImage<?= $row['id'] ?>(this.src)">
            <img src="admin/uploads/<?= $row['back_image'] ?>" onclick="changeImage<?= $row['id'] ?>(this.src)">
        </div>

        <h3><b><?= htmlspecialchars($row['title']) ?></b></h3>
        <h4>₹<?= $row['price'] ?></h4>
        <h6>Color:<?= htmlspecialchars($row['main_color']) ?></h6>

        <!-- Sizes -->
        <div style="display:flex; gap:10px;">
            <?php foreach ($sizes as $size) { ?>
                <label class="size-btn <?= $isOutOfStock ? 'disabled' : '' ?>">
                    <input type="radio" name="product_size_<?= $row['id'] ?>" value="<?= $size ?>" <?= $isOutOfStock ? 'disabled' : 'required' ?> style="display:none;">
                    <span><?= $size ?></span>
                </label>
            <?php } ?>
        </div>

        <div class="actions">

        <?php if ($isOutOfStock): ?>
            <!-- Out of Stock Label -->
            <span class="out-of-stock"><i class="fa-solid fa-ban"></i> Out of Stock</span>
        <?php else: ?>
            <!-- Add to Cart Form -->
            <form method="POST" action="add_to_cart.php" style="display:inline-block;">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="product_title" value="<?= htmlspecialchars($row['title']) ?>">
                <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
                <input type="hidden" name="product_main_color" value="<?= $row['main_color'] ?>">
                <input type="hidden" name="product_image" value="<?= $row['front_image'] ?>">
                <input type="hidden" name="product_quantity" value="1">
                <button type="submit" name="add_to_cart" class="btn-cart">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
            </form>

            <!-- Wishlist Form -->
            <form method="POST" action="wishlist.php" style="display:inline-block;">
                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="product_title" value="<?= htmlspecialchars($row['title']) ?>">
                <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
                <input type="hidden" name="product_image" value="<?= $row['front_image'] ?>">
                <input type="hidden" name="product_main_color" value="<?= $row['main_color'] ?>">
                <input type="hidden" name="product_size" class="wishlist-size">
                <input type="hidden" name="product_color" class="wishlist-color">
                <input type="hidden" name="product_qty" value="1">
                <button type="submit" name="add_to_wishlist" class="btn-wishlist" style="background:black;">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </form>
        <?php endif; ?>

        </div>
        <button class="btn view-details" data-id="<?= $row['id']; ?>">View Details</button>
    </div>
    <script>
        function changeImage<?= $row['id'] ?>(src) {
        document.getElementById("mainImage<?= $row['id'] ?>").src = src;
        }

    document.addEventListener('DOMContentLoaded', function () {
    // Attach handlers to each product-card once
    document.querySelectorAll('.product-card').forEach(function (card) {
        const sizeRadios = card.querySelectorAll('input[name="product_size"]');
        const colorRadios = card.querySelectorAll('input[name="product_color"]');
        const wishlistForm = card.querySelector('.wishlist-form');
        const wishlistSize = card.querySelector('.wishlist-size');
        const wishlistColor = card.querySelector('.wishlist-color');
        const mainColorInput = card.querySelector('input[name="product_main_color"]');
        const mainColor = mainColorInput ? mainColorInput.value : '';

        // Set hidden fields when user changes selection
        sizeRadios.forEach(r => r.addEventListener('change', function () {
        if (wishlistSize) wishlistSize.value = this.value;
        }));
        colorRadios.forEach(r => r.addEventListener('change', function () {
        if (wishlistColor) wishlistColor.value = this.value;
        }));

        // Safety-net: on submit, copy checked radios into hidden inputs (even if change wasn't fired)
        if (wishlistForm) {
        wishlistForm.addEventListener('submit', function (e) {
            // copy selected size if any
            const checkedSize = card.querySelector('input[name="product_size"]:checked');
            if (checkedSize) wishlistSize.value = checkedSize.value;

            // copy selected color if any (else fallback to main color)
            const checkedColor = card.querySelector('input[name="product_color"]:checked');
            if (checkedColor) wishlistColor.value = checkedColor.value;
            if (!wishlistColor.value) wishlistColor.value = mainColor;

            // OPTIONAL: enforce size selection before adding to wishlist
            // if you want to force the user to pick a size, uncomment the lines below:
            
            if (!wishlistSize.value) {
            alert('Please select a size before adding to wishlist.');
            e.preventDefault();
            return false;
            }
            
        });
        }
    });
    });
    </script>
<?php } ?>
</div>


  <!-- Modal -->
  <div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="productDetails">
          <p class="text-center">Loading...</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Add simple JS to highlight selected box
    const radios = document.querySelectorAll('input[name="product_size"]');
    radios.forEach(radio => {
      radio.addEventListener('change', () => {
        document.querySelectorAll('label').forEach(lbl => lbl.style.background = '');
        radio.parentElement.style.background = '#d1f7d6'; // highlight selected
      });
    });
  </script>
  <script>
    // This code for view ditails
    $(document).ready(function () {
      $(".view-details").click(function () {
        var productId = $(this).data("id");

        var myModal = new bootstrap.Modal(document.getElementById('productModal'));
        myModal.show();

        $("#productDetails").html("<p class='text-center'>Loading...</p>");
        $.ajax({
          url: "get_product_detail.php", // same page
          method: "POST",
          data: { ajax: "getProduct", id: productId },
          success: function (data) {
            $("#productDetails").html(data);
          },
          error: function () {
            $("#productDetails").html("<p class='text-danger'>Failed to load product details.</p>");
          }
        });
      });
    });
  </script>
  <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <?php include("footer.php"); ?>
</body>

</html>