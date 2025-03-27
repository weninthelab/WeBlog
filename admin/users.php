<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>Manage Users</h2>";
$result = mysqli_query($conn, "SELECT * FROM users");
echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['username']}</td>
        <td>{$row['email']}</td>
        <td>{$row['role']}</td>
        <td><a href='delete_user.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
echo "</table>";
include '../footer.php';
