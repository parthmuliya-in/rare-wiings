<?php include("header.php"); 

// check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $user_id = $_SESSION['user_id'];
    $rating = intval($_POST['rating']);
    $feedback = trim($_POST['feedback']);

    if ($rating > 0) {
        $stmt = $conn->prepare("INSERT INTO feedback (user_id, rating, feedback) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $rating, $feedback);
        if ($stmt->execute()) {
            $_SESSION['feedback_given'] = true; // ✅ mark feedback done
        }
    }
    header("Location: thankyou.php"); // refresh page to hide popup
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You</title>
  <link rel="icon" type="image/x-icon" href="images/logo/2-01.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <!--css link-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/form.css">
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
  <style>
  
    .thankyou {
      display: flex; justify-content: center; align-items: center;
      height: 100vh; text-align: center;
    }
    .thankyou-box {
      background: #fff; padding: 40px; border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .thankyou-box h1 { color: green; margin-bottom: 20px; }
.popup {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.popup-content {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 400px;
  max-width: 90%;
  text-align: center;
  position: relative;
}
.stars {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin: 15px 0;
}
.stars i {
  font-size: 30px;
  color: #ccc;
  cursor: pointer;
  transition: color 0.2s;
}
.stars i.active {
  color: gold;
}
textarea {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
}
button {
  margin-top: 15px;
  padding: 10px 20px;
  background: #222;
  color: #fff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}
.close-btn {
  position: absolute;
  top: 10px; right: 15px;
  font-size: 20px;
  cursor: pointer;
  color: #555;
}
.close-btn:hover {
  color: red;
}
  </style>
</head>
<body>
  <div class="thankyou">
    <div class="thankyou-box">
      <h1>🎉 Thank You!</h1>
      <p>Your payment was successful. We’ve received your order.</p>
      <!--<a href="index.php" class="btn btn-primary mt-3">Back to Home</a>-->
      <a href="invoice.php" class="btn btn-primary mt-3"><i class="fa-solid fa-download"></i>Download Invoice</a>
      <a href="dashboard.php" class="btn btn-primary mt-3">Go to My Profile</a>
    </div>
  </div>


<!-- ✅ Show popup only if feedback not given -->
<?php if (empty($_SESSION['feedback_given'])): ?>
<div class="popup" id="feedbackPopup">
  <div class="popup-content">
    <span class="close-btn" onclick="closePopup()">&times;</span>
    <h2>Rate Your Experience</h2>
    <form method="post">
      <div class="stars">
        <i class="fa fa-star" data-value="1"></i>
        <i class="fa fa-star" data-value="2"></i>
        <i class="fa fa-star" data-value="3"></i>
        <i class="fa fa-star" data-value="4"></i>
        <i class="fa fa-star" data-value="5"></i>
      </div>
      <input type="hidden" name="rating" id="rating" value="0">
      <textarea name="feedback" placeholder="Write your feedback..." rows="4"></textarea>
      <button type="submit" name="submit_feedback">Submit</button>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
// ✅ Show popup automatically if present
window.onload = function() {
  let popup = document.getElementById("feedbackPopup");
  if (popup) popup.style.display = "flex";
};

// ✅ Close popup
function closePopup() {
  document.getElementById("feedbackPopup").style.display = "none";
}

// ✅ Star rating selection
const stars = document.querySelectorAll(".stars i");
const ratingInput = document.getElementById("rating");

stars.forEach(star => {
  star.addEventListener("click", () => {
    const value = star.getAttribute("data-value");
    ratingInput.value = value;

    stars.forEach(s => s.classList.remove("active"));
    for (let i = 0; i < value; i++) {
      stars[i].classList.add("active");
    }
  });
});
</script>
</body>
</html>
<?php include("footer.php"); ?>
