<link rel="stylesheet" href="assets/css/post.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<?php
include 'includes/header.php';
if (!is_logged_in()) {
    echo "<script>
        alert('Please login first!');
        window.location.href = 'auth/login.php';
    </script>";
    exit;
}

$uid = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $post = $conn->query("
        SELECT posts.*, users.username, users.avatar_path, posts.premium 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = $id
    ")->fetch_assoc();


    if ($post['premium']) {
        $is_premium = $conn->query("
            SELECT s.id 
            FROM subscriptions s 
            JOIN status_descriptions sd ON s.status_id = sd.id 
            WHERE s.user_id = $uid AND sd.status = 'active' 
            AND s.end_date > NOW()
        ")->num_rows > 0;

        if (!$is_premium) {
            echo "<script>
                alert('This is a premium post. Please subscribe to access.');
                window.location.href = 'subscription.php';
            </script>";
            exit;
        }
    }

    $conn->query("UPDATE posts SET views = views + 1 WHERE id = $id");
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
    <?php
    $role = $_SESSION['role'] ?? 3;
    ?>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="mb-4 text-center">📝 Create a New Post</h2>
            <form id="createPostForm" method="post" action="actions/create_post.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" id="title" name="title" class="form-control rounded-pill px-3" required placeholder="Enter post title...">
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="5" required placeholder="Write something interesting..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="thumbnail" class="form-label fw-bold">Thumbnail</label>
                    <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
                </div>

                <?php if ($role == 'premium'): ?>
                    <div class="form-check mb-3">
                        <input type="checkbox" id="premium" name="premium" class="form-check-input">
                        <label for="premium" class="form-check-label">Make this a premium post</label>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary w-100 fw-bold">🚀 Post Now</button>
            </form>
            <p id="postStatus" class="mt-3 text-center text-muted"></p>
        </div>
    </div>


<?php
}
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/main.js"></script>