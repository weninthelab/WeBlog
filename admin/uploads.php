<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>View Uploads</h2>";
$result = mysqli_query($conn, "SELECT uploads.*, users.username FROM uploads 
LEFT JOIN users ON uploads.user_id = users.id");
echo "<table border='1'><tr><th>ID</th><th>User</th><th>File Name</th><th>File Path</th><th>Uploaded At</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['file_name']}</td>
        <td><a href='../{$row['file_path']}' target='_blank'>{$row['file_path']}</a></td>
        <td>{$row['uploaded_at']}</td>
    </tr>";
}
echo "</table>";
include '../footer.php';
