<?php include 'includes/header.php'; ?>
<h2>Inbox</h2>
<?php
$uid = $_SESSION['user_id'] ?? 0;
$result = $conn->query("SELECT * FROM messages WHERE receiver_id = $uid");
while ($msg = $result->fetch_assoc()) {
    echo "<p><b>{$msg['subject']}</b>: {$msg['message']}</p>";
}
?>
<h3>Send Message</h3>
<form method="post">
    To (user_id): <input name="receiver_id"><br>
    Subject: <input name="subject"><br>
    Message: <textarea name="message"></textarea><br>
    <button type="submit">Send</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender = $_SESSION['user_id'];
    $receiver = $_POST['receiver_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $conn->query("INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES ($sender, $receiver, '$subject', '$message')");
}
include 'includes/footer.php';
?>
