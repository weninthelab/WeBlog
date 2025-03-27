<?php
include_once '../config.php'; 
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];
$user_id = $_SESSION['user_id'] ?? 0;
$conn->query("INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')");
header("Location: " . BASE_URL . "/post.php?id=$post_id");
