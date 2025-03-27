<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>View Messages</h2>";
$result = mysqli_query($conn, "SELECT messages.*, u1.username AS sender, u2.username AS receiver FROM messages 
LEFT JOIN users AS u1 ON messages.sender_id = u1.id 
LEFT JOIN users AS u2 ON messages.receiver_id = u2.id");
echo "<table border='1'><tr><th>ID</th><th>Sender</th><th>Receiver</th><th>Subject</th><th>Message</th><th>Sent At</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['sender']}</td>
        <td>{$row['receiver']}</td>
        <td>{$row['subject']}</td>
        <td>{$row['message']}</td>
        <td>{$row['sent_at']}</td>
    </tr>";
}
echo "</table>";
include '../footer.php';
