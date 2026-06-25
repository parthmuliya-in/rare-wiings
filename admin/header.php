<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Bootstrap (optional, safe now) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../images/logo/2-01.png">

  <!-- Custom Navbar CSS -->
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  
  <!-- Rare Wiing Navbar -->
  <nav class="rw-navbar">
    <div class="rw-logo">Rare Wiing</div>

    <ul class="rw-nav-links" id="rwNavLinks">
      <li><a href="index.php">Home</a></li>
      <li><a href="view_order.php">Orders</a></li>

      <li class="rw-has-dropdown">
        <a href="manage_products.php">Products ▾</a>
        <ul class="rw-dropdown">
          <li><a href="manage_products.php">Manage Product</a></li>
          <li><a href="view_products.php">View Product</a></li>
        </ul>
      </li>

      <li class="rw-has-dropdown">
        <a href="user.php"> Members ▾</a>
        <ul class="rw-dropdown">
          <li><a href="user.php">Users</a></li>
          <li><a href="subscriber.php">Subscribers</a></li>
        </ul>
      </li>

      <li><a href="manage_chatbot.php">Chat Bot</a></li>
      <li><a href="coupons.php">Coupons</a></li>
      <li><a href="feedback.php">Feedback</a></li>
      <li><a href="help.php">Help</a></li>
      <li><a href="logout.php" class="rw-logout"><i class="fa-solid fa-right-from-bracket" style="color:red;"></i> </a></li>
    </ul>

    <div class="rw-hamburger" id="rwHamburger" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </div>
  </nav>

  <!-- JS -->
  <script src="js/rw-navbar.js"></script>
</body>
</html>
