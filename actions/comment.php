<?php
include_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    die("Login to comment!");
}


if (!isset($_POST['post_id'], $_POST['comment']) || empty(trim($_POST['comment']))) {
    die("Invalid data!");
}

$post_id = $_POST['post_id'];
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];


if (!filter_var($post_id, FILTER_VALIDATE_INT)) {
    die("Invalid ID!");
}


$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $user_id, $comment);

if ($stmt->execute()) {
    header("Location: " . BASE_URL . "/post.php?id=$post_id");
    exit;
} else {
    die("Error comment: " . $stmt->error);
}
