<?php
include_once __DIR__ . '/../config.php';
include BASE_PATH . '/includes/functions.php';

if (!is_logged_in()) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$title = $_POST['title'];
$content = $_POST['content'];
$base_path = BASE_PATH;
// Lưu bài viết vào database trước để lấy ID
$conn->query("INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')");
$post_id = $conn->insert_id;

// Xử lý upload ảnh thumbnail
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "$base_path/uploads/$username/thumbnails/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $target_file = $upload_dir . "$post_id.jpg";
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_file);

    // Cập nhật đường dẫn thumbnail vào database
    $thumb_path = "uploads/$username/thumbnails/$post_id.jpg";
    $conn->query("UPDATE posts SET thumb_path = '$thumb_path' WHERE id = $post_id");
}
