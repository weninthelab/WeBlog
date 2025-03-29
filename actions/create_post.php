<?php
require '../config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $premium = isset($_POST['premium']) ? 1 : 0;
    $thumbnail_path = "assets/images/default_thumbnail.jpg";


    if (!$conn) {
        die("Lỗi kết nối: " . mysqli_connect_error());
    }


    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, premium, thumbnail_path) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("issis", $user_id, $title, $content, $premium, $thumbnail_path);
    if (!$stmt->execute()) {
        die("Lỗi: " . $stmt->error);
    }


    $post_id = $stmt->insert_id;
    echo "DEBUG: post_id = $post_id<br>";
    $stmt->close();

    if (!empty($_FILES['thumbnail']['name'])) {
        $target_dir =  "../auth/uploads/$username/thumbnails/";


        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }


        $file_extension = pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION);
        $target_file = $target_dir . $post_id . '.' . $file_extension;

        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            echo "File uploaded successfully: $target_file<br>";


            $relative_path = str_replace("../", "", $target_file);

            $stmt = $conn->prepare("UPDATE posts SET thumbnail_path = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("si", $relative_path, $post_id);
                if ($stmt->execute()) {
                    echo "Thumbnail path updated in database.<br>";
                } else {
                    echo "Database update failed: " . $stmt->error . "<br>";
                }
                $stmt->close();
            } else {
                echo "Prepare statement failed: " . $conn->error . "<br>";
            }
        } else {
            echo "File upload failed.<br>";
        }
    }
    header("Location: ../index.php");
    exit();
    $conn->close();
}
