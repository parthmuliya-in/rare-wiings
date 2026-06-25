<link rel="icon" type="image/x-icon" href="images/logo/2-01.png">

<!--css link-->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/form.css">
<link rel="stylesheet" href="css/about.css">
<link rel="stylesheet" href="css/product.css">
<!--font awesome link-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
  integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
<!--google fonts link-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
  rel="stylesheet">
<!--first part start-->
<div class="rw-first-nav">
  <div class="rw-login-div">
    <?php
    session_start();
    include("config/config.php");
    if (isset($_SESSION['fname'])) {
      echo "<a href='dashboard.php'>👋 " . $_SESSION['fname'] . "</a>";
    } elseif (isset($_SESSION['guest'])) {
      echo "<i class='fa-solid fa-user-secret'></i> Welcome, Guest";
    } else {
      echo "<a href='login.php'><i class='fa-solid fa-circle-user'></i> Login</a>";
    }

    if (isset($_POST["submit"])) {
    // Trim and sanitize inputs
    $f_name = htmlspecialchars(trim($_POST['f_name']));
    $l_name = htmlspecialchars(trim($_POST['l_name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $terms = isset($_POST['terms']) ? 1 : 0;

    if (!$email) {
        $message = ['type' => 'error', 'text' => 'Please enter a valid email address.'];
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM subscribe WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = ['type' => 'error', 'text' => "You have already subscribed with this email."];
        } else {
            // Insert new subscription
            $insert_stmt = $conn->prepare("INSERT INTO subscribe (first_name, last_name, email, terms) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("sssi", $f_name, $l_name, $email, $terms);

            if ($insert_stmt->execute()) {
                $last_id = $insert_stmt->insert_id;
                $message = ['type' => 'success', 'text' => "Subscription successful! Your ID is: $last_id"];
            } else {
                $message = ['type' => 'error', 'text' => "Error: " . $insert_stmt->error];
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
}
    ?>
  </div>
  <div class="rw-text-logo">
    <h1>rare wiing</h1>
  </div>
  <div class="rw-shopping-bag">
    <a href="wishlist.php"><i class="fa-regular fa-heart"></i></a>
    <a href="cart.php"><i class="fa-solid fa-bag-shopping"></i>
      <?php
      $cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
      if ($cart_count > 0) {
        echo "<span class='rw-cart-count'>$cart_count</span>";
      }
      ?>
    </a>
  </div>
</div>
<!--first part close-->

<!--navbar part start-->
<header class="rw-header">
  <div class="rw-container">
    <input type="checkbox" id="rw-check">
    <label for="rw-check" class="rw-hamburger-menu-container">
      <div class="rw-hamburger-menu">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </label>

    <div class="rw-nav-btn">
      <div class="rw-nav-links">
        <ul>
          <li class="rw-nav-link"><a href="index.php">Home</a></li>
          <li class="rw-nav-link"><a href="about.php">About</a></li>
          <li class="rw-nav-link"><a href="product.php">Arc Product</a></li>
          <li class="rw-nav-link"><a href="shipping-policy.php">Shipping Policy</a></li>
          <li class="rw-nav-link"><a href="return-policy.php">Return Policy</a></li>
          <li class="rw-nav-link"><a href="social-media.php">Social Medias</a></li>
          <li class="rw-nav-link"><a href="discount.php">Discounts</a></li>
          <li class="rw-nav-link" style="--i: 1.35s">
            <?php
            if (isset($_SESSION['fname'])) {
              echo "<a href='logout.php' class='rw-nav-link logout-link'>
                          <i class='fa-solid fa-right-from-bracket'></i>
                          </a>";
            } else {
              if (isset($_SESSION['guest'])) {
                echo "<a href='logout.php' class='rw-nav-link logout-link'>
                          <i class='fa-solid fa-right-from-bracket'></i>
                          </a>";
              }
            }
            ?>
          </li>
        </ul>
      </div>
    </div>
  </div>
</header>
<!-- Display message box with Font Awesome icon -->
<?php if(isset($message)): ?>
<div class="rw-message-box <?= $message['type'] ?>">
    <i class="fa <?= ($message['type']=='success') ? 'fa-circle-check' : 'fa-circle-exclamation' ?>"></i>
    <?= $message['text'] ?>
</div>
<?php endif; ?>

<!--navbar part close-->