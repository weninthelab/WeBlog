<?php
include 'db.php';
include 'header.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

echo "<h2>Logs</h2>";
$result = mysqli_query($conn, "SELECT logs.*, users.username FROM logs LEFT JOIN users ON logs.user_id = users.id ORDER BY created_at DESC");

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>User</th><th>Action</th><th>IP Address</th><th>Time</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['action']}</td>
        <td>{$row['ip_address']}</td>
        <td>{$row['created_at']}</td>
    </tr>";
}
echo "</table>";

include 'footer.php';
