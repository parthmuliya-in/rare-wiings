<?php
include("header.php");
include("chatbot.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rare wiing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <!--second part start-->

    <div class="images-second">
        <div class="inner-second">
            <img src="images/second-1.jpg" alt="">
        </div>
        <div class="inner-second">
            <img src="images/second-2.jpg" alt="">
        </div>
    </div>

    <!--second part close-->

    <!--3rd part start-->
    <?php
    include("config/config.php"); // DB connection
    
    // Default query
    $query = "SELECT * FROM products WHERE out_stock = 1 ORDER BY created_at DESC";


    $result = mysqli_query($conn, $query);
    ?>
    <div class="third-head">
        <h1>Pre-Order Our Collection</h1>
    </div>


    <div class="third-section">
        <div class="section1">
            <a href="product.php"><img src="products-page-images/Hoodies/Aether Hoodies/black/b-back-hoodies.png"
                    alt=""></a>
            <a href="product.php">
                <h1>Hoodies</h1>
            </a>
        </div>
        <div class="section1">
            <a href="product.php"><img src="products-page-images/Premium collection/Aura fitted tee/pre full sleeve.png"
                    alt=""></a>
            <a href="product.php">
                <h1>Fitted-tees</h1>
            </a>
        </div>
        <div class="section1">
            <a href="product.php"><img src="products-page-images/Sweatshirts/Arc Sweatshirts/black/b-front.png"
                    alt=""></a>
            <a href="product.php">
                <h1>Sweatshirts</h1>
            </a>
        </div>
        <div class="section1">
            <a href="product.php"> <img src="products-page-images/tshirts/Arc tee/black/b-front.png" alt=""></a>
            <a href="product.php">
                <h1>T-shirts</h1>
            </a>
        </div>
        <div class="section1">
            <a href="product.php"><img
                    src="products-page-images/Premium collection/Aurelia Sweatshirts/black-premium.png" alt=""></a>
            <a href="product.php">
                <h1>Premium Collection</h1>
            </a>
        </div>
    </div>
    <!--3rd part close-->

    <!--contact form start-->
    <div class="contact-head">
        <h2>Subscribe to rare wiing</h2>
    </div>
    <div class="contact-form">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-div">
                <div class="form1">
                    <label for="">First Name</label><br>
                    <input type="text" placeholder="First name" name="f_name" id="fname" required>
                </div>
                <div class="form1">
                    <label for="">Last Name</label><br>
                    <input type="text" placeholder="Last name" name="l_name" id="lname" required>
                </div>
                <div class="form1">
                    <label for="">Email</label><br>
                    <input type="email" placeholder="email" name="email" id="email" required>
                </div>
            </div>
            <input type="checkbox" name="terms" id="term" value="1" required>
            <span>I want to subscribe to your mailing list.</span>
            <br>
            <button type="submit" name="submit" value="Submit"><span>Submit</span></button>
        </form>
    </div>
    <!--contact form close-->
    <!--FAQ part start-->
    <?php
    include("faq.php");
    ?>
    <!--FAQ part End-->

    <!--icon part start-->
    <?php
    include("footer.php");
    ?>
    <!--icon part close-->
</body>

</html>