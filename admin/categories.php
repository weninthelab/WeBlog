<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>Manage Categories</h2>";
$result = mysqli_query($conn, "SELECT * FROM categories");
echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['description']}</td>
        <td><a href='delete_category.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
echo "</table>";
include '../footer.php';
