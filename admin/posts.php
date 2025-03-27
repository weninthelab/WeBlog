<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>Manage Posts</h2>";
$result = mysqli_query($conn, "SELECT posts.*, users.username FROM posts LEFT JOIN users ON posts.user_id = users.id");
echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Author</th><th>Views</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['username']}</td>
        <td>{$row['views']}</td>
        <td><a href='delete_post.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
echo "</table>";
include '../footer.php';
