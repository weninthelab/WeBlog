<?php
include 'config.php';
include 'includes/header.php';

$userId = $_SESSION['user_id'] ?? null;

if (isset($_GET['id'])) {
    $authorId = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT username, email, bio, avatar_path, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $authorId);
    $stmt->execute();
    $author = $stmt->get_result()->fetch_assoc();

    if (!$author) {
        echo "<div class='container'><h2>User not found.</h2></div>";
        include 'includes/footer.php';
        exit();
    }

    $posts = $conn->query("SELECT id, title, created_at, views, premium FROM posts WHERE user_id = $authorId ORDER BY created_at DESC");
} else {
    echo "<div class='container'><h2>No author selected.</h2></div>";
    include 'includes/footer.php';
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar']) && $userId === $authorId) {
    $uploadDir = "auth/uploads/" . $author['username'];
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $avatarPath = $uploadDir . "/avatar.png";

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
        $stmt = $conn->prepare("UPDATE users SET avatar_path = ? WHERE id = ?");
        $stmt->bind_param("si", $avatarPath, $userId);
        $stmt->execute();
        $_SESSION['success'] = "Avatar updated successfully.";
        header("Location: author.php?id=" . $authorId);
        exit();
    } else {
        $_SESSION['error'] = "Failed to upload avatar.";
    }
}
?>

<link rel="stylesheet" href="assets/css/profile.css">

<div class="profile-container">
    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($author['avatar_path']); ?>" alt="Avatar">
        <h2><?php echo htmlspecialchars($author['username']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($author['email']); ?></p>
        <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($author['bio'])); ?></p>
        <p class="created-at">Joined on <?php echo date("M d, Y", strtotime($author['created_at'])); ?></p>

        <?php if ($userId === $authorId) { ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="avatar">Change Avatar:</label>
                <input type="file" name="avatar" id="avatar" accept="image/*">
                <button type="submit">Save</button>
            </form>
        <?php } ?>
    </div>

    <div class="author-posts">
        <h3>Posts</h3>
        <?php if ($posts->num_rows > 0) { ?>
            <ul class="post-list">
                <?php while ($post = $posts->fetch_assoc()) { ?>
                    <li>
                        <a href="post.php?id=<?php echo $post['id']; ?>">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                        <span class="meta">
                            • <?php echo date("M d, Y", strtotime($post['created_at'])); ?>
                            • Views: <?php echo $post['views']; ?>
                            <?php if ($post['premium']) echo " • <span class='premium'>Premium</span>"; ?>
                        </span>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>This user has not posted anything yet.</p>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>