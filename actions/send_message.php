<?php
include_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender = $_SESSION['user_id'] ?? 0;
    $receiver_username = $_POST['username'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Lấy ID của người nhận từ username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $receiver_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $receiver_id = $row['id'];

        // Chèn tin nhắn vào DB
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $sender, $receiver_id, $subject, $message);

        if ($stmt->execute()) {
            echo "<p class='success'>Message sent to <b>$receiver_username</b>!</p>";
        } else {
            echo "<p class='error'>Failed to send message!</p>";
        }
    } else {
        echo "<p class='error'>User not found!</p>";
    }
} else {
    echo "<p class='error'>Invalid request!</p>";
}
