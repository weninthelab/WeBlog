<?php
include '../db.php';
include '../header.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

echo "<h2>Admin Dashboard</h2>";
echo "<ul>
<li><a href='users.php'>Manage Users</a></li>
<li><a href='posts.php'>Manage Posts</a></li>
<li><a href='comments.php'>Manage Comments</a></li>
<li><a href='categories.php'>Manage Categories</a></li>
<li><a href='messages.php'>View Messages</a></li>
<li><a href='uploads.php'>View Uploads</a></li>
<li><a href='../logs.php'>View Logs</a></li>
</ul>";

include '../footer.php';
