<?php
// Database connection
include("config/config.php");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $user_msg = mysqli_real_escape_string($conn, $_POST['message']);

    // Exact match / LIKE search
    $res = mysqli_query($conn, "SELECT answer FROM faq WHERE question LIKE '$user_msg' LIMIT 1");

    if ($row = mysqli_fetch_assoc($res)) {
        $answer = $row['answer'];
    } else {
       $answer = "<i class='fa-regular fa-handshake'></i> Our support team will assist you directly. Thank you!<br>
                   <i class='fa-solid fa-phone'></i> Phone: +1 234 567 890<br>
                   <i class='fa-regular fa-envelope'></i> Email: support@rarewiing.com<br>
                   <i class='fa-solid fa-user'></i> Contact: John Smith";
    }

    echo json_encode(['answer' => $answer]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rare Wiing</title>
    <link rel="stylesheet" href="css/chat.css">
    <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <!-- Chat icon -->

    <div id="chatIcon"><i class="fa-regular fa-comment"></i></div>

    <!-- Chat box -->
    <div id="chatBox">
        <div id="chatHeader">Support Chat</div>
        <div id="chatMessages"></div>
        <div class="typing-indicator" id="typing">Typing...</div>
        <div id="chatInput">
            <input type="text" id="userInput" placeholder="Type your message...">
            <button id="sendBtn">Send</button>
        </div>
    </div>
    <script src="js/chat.js"></script>
</body>

</html>