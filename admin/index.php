<?php
include_once '../config.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

// Fetch general statistics
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts")->fetch_assoc()['total'] ?? 0;
$total_comments = $conn->query("SELECT COUNT(*) AS total FROM comments")->fetch_assoc()['total'] ?? 0;

// Calculate average comments per post
$avg_comments_per_post = $total_posts > 0 ? floor($total_comments / $total_posts) : 0;

// Fetch the most viewed post
$most_viewed_post = $conn->query("SELECT title, views FROM posts ORDER BY views DESC LIMIT 1")->fetch_assoc() ?? ['title' => 'N/A', 'views' => 0];

// Fetch the post with the most comments (Fix: Use LEFT JOIN to avoid missing posts without comments)
$most_commented_post = $conn->query(
    "SELECT posts.title, COUNT(comments.id) AS total_comments 
    FROM posts 
    LEFT JOIN comments ON posts.id = comments.post_id 
    GROUP BY posts.id 
    ORDER BY total_comments DESC 
    LIMIT 1"
)->fetch_assoc() ?? ['title' => 'N/A', 'total_comments' => 0];

// Fetch the newest post
$newest_post = $conn->query("SELECT title, created_at FROM posts ORDER BY created_at DESC LIMIT 1")->fetch_assoc() ?? ['title' => 'N/A', 'created_at' => 'N/A'];

// Fetch the user with the most posts
$top_poster = $conn->query(
    "SELECT users.username, COUNT(posts.id) AS total_posts 
    FROM users 
    LEFT JOIN posts ON users.id = posts.user_id 
    GROUP BY users.id 
    ORDER BY total_posts DESC 
    LIMIT 1"
)->fetch_assoc() ?? ['username' => 'N/A', 'total_posts' => 0];

// Fetch the user with the most comments
$top_commenter = $conn->query(
    "SELECT users.username, COUNT(comments.id) AS total_comments 
    FROM users 
    LEFT JOIN comments ON users.id = comments.user_id 
    GROUP BY users.id 
    ORDER BY total_comments DESC 
    LIMIT 1"
)->fetch_assoc() ?? ['username' => 'N/A', 'total_comments' => 0];

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<?php include_once BASE_PATH . '/includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Welcome, <?php echo $_SESSION['username'] ?>!</h1>
    <p class="lead">Below are system statistics:</p>

    <div class="row text-center">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">👤 Total Users</h5>
                    <p class="card-text display-6"><?= htmlspecialchars($total_users) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">📝 Total Posts</h5>
                    <p class="card-text display-6"><?= htmlspecialchars($total_posts) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h5 class="card-title">💬 Avg Comments/Post</h5>
                    <p class="card-text display-6"><?= htmlspecialchars($avg_comments_per_post) ?></p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-4">Top Statistics</h2>
    <ul class="list-group">
        <li class="list-group-item">🔥 Most Viewed Post: <strong><?= htmlspecialchars($most_viewed_post['title']) ?></strong> (<?= htmlspecialchars($most_viewed_post['views']) ?> views)</li>
        <li class="list-group-item">💬 Most Commented Post: <strong><?= htmlspecialchars($most_commented_post['title']) ?></strong> (<?= htmlspecialchars($most_commented_post['total_comments']) ?> comments)</li>
        <li class="list-group-item">🆕 Newest Post: <strong><?= htmlspecialchars($newest_post['title']) ?></strong> (Published on <?= htmlspecialchars($newest_post['created_at']) ?>)</li>
        <li class="list-group-item">🏆 User with Most Posts: <strong><?= htmlspecialchars($top_poster['username']) ?></strong> (<?= htmlspecialchars($top_poster['total_posts']) ?> posts)</li>
        <li class="list-group-item">🗨️ User with Most Comments: <strong><?= htmlspecialchars($top_commenter['username']) ?></strong> (<?= htmlspecialchars($top_commenter['total_comments']) ?> comments)</li>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include_once BASE_PATH . '/includes/footer.php'; ?>