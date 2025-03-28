<link rel="stylesheet" href="assets/css/post.css">
<?php
include 'includes/header.php';
if (!is_logged_in()) {
    echo "<script>
        alert('Vui lòng đăng nhập để tiếp tục.');
        window.location.href = 'auth/login.php';
    </script>";
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("UPDATE posts SET views = views + 1 WHERE id = $id");
    $post = $conn->query("SELECT posts.*, users.username, users.avatar_path FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $id")->fetch_assoc();
?>

    <div class="post-container">
        <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="post-meta">
            <img src="<?= $post['avatar_path'] ?>" alt="avatar" class="post-avatar">
            <b><span class="post-author"><?= htmlspecialchars($post['username']) ?></span></b>
            <span class="post-date"><?= date('d M Y H:i', strtotime($post['created_at'])) ?></span>
            <span class="post-views"><?= $post['views'] ?> views</span>
        </div>
        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <h3>Comments</h3>
        <div class="comments-section">
            <?php
            $result = $conn->query("
            SELECT comments.*, users.username, users.avatar_path 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE post_id = $id ORDER BY created_at DESC
        ");
            while ($cmt = $result->fetch_assoc()) {
            ?>
                <div class="comment-item">
                    <img src="<?= $cmt['avatar_path'] ?>" alt="avatar" class="comment-avatar">
                    <div class="comment-body">
                        <div class="comment-header">
                            <span class="comment-author"><?= htmlspecialchars($cmt['username']) ?></span>
                            <span class="comment-date"><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></span>
                        </div>
                        <p class="comment-text"><?= nl2br(htmlspecialchars($cmt['comment'])) ?></p>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <?php if (is_logged_in()): ?>
            <h4>Leave a Comment</h4>
            <form method="post" action="actions/comment.php" class="comment-form">
                <input type="hidden" name="post_id" value="<?= $id ?>">
                <textarea name="comment" placeholder="Write your comment..." required></textarea><br>
                <button type="submit" class="comment-button">Comment</button>
            </form>
        <?php else: ?>
            <p>Please <a href="auth/login.php">log in</a> to comment.</p>
        <?php endif; ?>
    </div>
<?php
}

if (isset($_GET['new']) && is_logged_in()) {
?>
    <div class="new-post-container">
        <h2>Create a New Post</h2>
        <form id="createPostForm" method="post" action="actions/create_post.php" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>

            <label for="thumbnail">Thumbnail:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" required>

            <button type="submit">Post</button>
        </form>
        <p id="postStatus"></p>
    </div>

<?php
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/main.js"></script>