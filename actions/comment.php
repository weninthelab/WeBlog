<?php
session_start(); // Bắt đầu session nếu chưa có
include_once '../config.php';

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để bình luận.");
}

// Kiểm tra dữ liệu đầu vào
if (!isset($_POST['post_id'], $_POST['comment']) || empty(trim($_POST['comment']))) {
    die("Dữ liệu không hợp lệ.");
}

$post_id = $_POST['post_id'];
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];

// Kiểm tra xem post_id có hợp lệ không
if (!filter_var($post_id, FILTER_VALIDATE_INT)) {
    die("ID bài viết không hợp lệ.");
}

// Chuẩn bị truy vấn để tránh SQL Injection
$stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $post_id, $user_id, $comment);

if ($stmt->execute()) {
    header("Location: " . BASE_URL . "/post.php?id=$post_id");
    exit;
} else {
    die("Lỗi khi thêm bình luận: " . $stmt->error);
}
?>
