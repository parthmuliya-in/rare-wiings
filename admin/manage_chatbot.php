<?php
session_start();
include("../config/config.php");
include("header.php");

// 🛡️ Secure login check
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

/* -------------------------------
   🛡️ Secure Message Helper
--------------------------------*/
function setMessage($type, $text)
{
  $_SESSION['message'] = ['type' => $type, 'text' => $text];
}

/* -------------------------------
   ➕ ADD FAQ
--------------------------------*/
if (isset($_POST['add_faq'])) {
  $question = trim($_POST['question']);
  $answer = trim($_POST['answer']);

  if (empty($question) || empty($answer)) {
    setMessage('error', '<i class="fa-solid fa-triangle-exclamation"></i> Both fields are required.');
  } else {
    $stmt = $conn->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
    $stmt->bind_param("ss", $question, $answer);
    if ($stmt->execute()) {
      setMessage('success', '<i class="fa-solid fa-square-check"></i> Chat added successfully.');
    } else {
      setMessage('error', '<i class="fa-solid fa-circle-exclamation"></i> Error: ' . $stmt->error);
    }
    $stmt->close();
  }
  header("Location: manage_chatbot.php");
  exit;
}

/* -------------------------------
   🗑️ DELETE FAQ
--------------------------------*/
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM faq WHERE id = ?");
  $stmt->bind_param("i", $id);
  if ($stmt->execute()) {
    setMessage('success', '<i class="fa-solid fa-trash"></i> Chat deleted successfully.');
  } else {
    setMessage('error', '<i class="fa-solid fa-circle-exclamation"></i> Failed to delete chat.');
  }
  $stmt->close();
  header("Location: manage_chatbot.php");
  exit;
}

/* -------------------------------
   ✏️ UPDATE FAQ
--------------------------------*/
if (isset($_POST['update_faq'])) {
  $id = (int) $_POST['id'];
  $question = trim($_POST['question']);
  $answer = trim($_POST['answer']);

  if (empty($question) || empty($answer)) {
    setMessage('error', '<i class="fa-solid fa-triangle-exclamation"></i> Both fields are required.');
  } else {
    $stmt = $conn->prepare("UPDATE faq SET question = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("ssi", $question, $answer, $id);
    if ($stmt->execute()) {
      setMessage('success', '<i class="fa-solid fa-pen-to-square"></i> Chat updated successfully.');
    } else {
      setMessage('error', '<i class="fa-solid fa-circle-exclamation"></i> Update failed.');
    }
    $stmt->close();
  }
  header("Location: manage_chatbot.php");
  exit;
}

/* -------------------------------
   📋 FETCH ALL FAQ
--------------------------------*/
$result = $conn->query("SELECT * FROM faq ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>FAQ Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="css/admin.css">
  <script src="js/message-box.js" defer></script>
</head>

<body>

  <h2>FAQ Management</h2>

  <!-- ✅ ADD FAQ FORM -->
  <form method="POST" class="faq-form">
    <input type="text" name="question" placeholder="Enter question" required>
    <input type="text" name="answer" placeholder="Enter answer" required>
    <button type="submit" name="add_faq">
      <i class="fa-solid fa-plus" style="color:#068df5;"></i> Add Chat
    </button>
  </form>

  <!-- 📄 FAQ TABLE -->
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Question</th>
      <th>Answer</th>
      <th>Actions</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['question']) ?></td>
          <td><?= htmlspecialchars($row['answer']) ?></td>
          <td>
            <!-- ✏️ UPDATE FORM -->
            <form method="POST" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="text" name="question" value="<?= htmlspecialchars($row['question']) ?>" required>
              <input type="text" name="answer" value="<?= htmlspecialchars($row['answer']) ?>" required>
              <button type="submit" name="update_faq">
                <i class="fa-solid fa-pen-to-square"></i> Update
              </button>
            </form>
            <!-- 🗑️ DELETE -->
            <a href="view_chatbot.php?delete=<?= $row['id'] ?>"
              onclick="return confirm('Are you sure you want to delete this?');">
              <i class="fa-solid fa-trash" style="color:red;"></i> Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr>
        <td colspan="4" style="text-align:center;">No FAQs found.</td>
      </tr>
    <?php endif; ?>
  </table>

  <!-- 💬 MESSAGE BOX -->
  <?php if (isset($_SESSION['message'])): ?>
    <?php $msg = $_SESSION['message'];
    unset($_SESSION['message']); ?>
    <div class="message-box <?= htmlspecialchars($msg['type']) ?> show">
      <?= $msg['text'] ?>
    </div>
  <?php endif; ?>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const msgBox = document.querySelector(".message-box");
      if (msgBox) {
        msgBox.style.display = "block";
        setTimeout(() => {
          msgBox.style.display = "none";
        }, 4000);
      }
    });

  </script>

</body>

</html>

<?php $conn->close(); ?>