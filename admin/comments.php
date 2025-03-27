<?php
include '../db.php';
include '../header.php';
session_start();
if ($_SESSION['role'] !== 'admin') die('Access Denied');

echo "<h2>Manage Comments</h2>";
$result = mysqli_query($conn, "SELECT comments.*, users.username, posts.title FROM comments 
LEFT JOIN users ON comments.user_id = users.id 
LEFT JOIN posts ON comments.post_id = posts.id");
echo "<table border='1'><tr><th>ID</th><th>Post</th><th>User</th><th>Comment</th><th>Actions</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['title']}</td>
        <td>{$row['username']}</td>
        <td>{$row['comment']}</td>
        <td><a href='delete_comment.php?id={$row['id']}'>Delete</a></td>
    </tr>";
}
echo "</table>";
include '../footer.php';
