<?php include 'includes/header.php';
if (!is_logged_in()) {
    echo "<script>
        alert('Vui lòng đăng nhập để tiếp tục.');
        window.location.href = 'auth/login.php';
    </script>";
    exit;
}
?>
<link rel="stylesheet" href="assets/css/message.css">


<div class="message-wrapper">

    <div class="message-container">
        <h2>Inbox</h2>
        <?php
        $uid = $_SESSION['user_id'] ?? 0;
        $query = "SELECT m.subject, m.message, u.username, u.avatar_path
          FROM messages m 
          JOIN users u ON m.sender_id = u.id 
          WHERE m.receiver_id = ? 
          ORDER BY m.sent_at DESC";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Query preparation failed: " . $conn->error);
        }

        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($msg = $result->fetch_assoc()) {
            echo "<div class='message'>
                <img src='{$msg['avatar_path']}' alt='avatar' class='avatar'>
                <div class='message-content'>
                    <div class='message-info'>
                         <div class='username'><b>{$msg['username']}</b></div>
                         <div class='subject'><b>Subject: {$msg['subject']}</b></div>
                    </div>
                <div class='message-body'>{$msg['message']}</div>
                </div>
              </div>";
        }
        ?>
    </div>
    <div class="send-message">
        <h3>Send Message</h3>
        <form id="sendMessageForm">
            To (username): <input name="username" required><br>
            Subject: <input name="subject" required><br>
            Message: <textarea name="message" required></textarea><br>
            <button type="submit">Send</button>
        </form>
        <p id="messageStatus"></p>

    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/main.js"></script>