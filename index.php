<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/main.css">

<div class="container layout-3col">
    <!-- Sidebar trái (Top Authors) -->
    <aside class="sidebar-left">
        <h4>🌟 Top Authors</h4>
        <ul class="author-list">
            <?php
            $authorResult = $conn->query("
                SELECT users.id, users.username, COUNT(posts.id) AS post_count
                FROM users
                JOIN posts ON users.id = posts.user_id
                GROUP BY users.id, users.username
                ORDER BY post_count DESC
                LIMIT 5
            ");
            while ($author = $authorResult->fetch_assoc()) {
                $authorName = htmlspecialchars($author['username']);
                echo "<li><a href='author.php?id={$author['id']}'>{$authorName}</a></li>";
            }
            ?>
        </ul>
    </aside>

    <!-- Nội dung chính (Recent Posts) -->
    <main class="main-content">
        <h2 class="section-title">✨ Recent Posts</h2>
        <div class="posts-grid">
            <?php
            $result = $conn->query("
                SELECT posts.*, users.username AS author 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY created_at DESC
                LIMIT 9
            ");
            if ($result->num_rows > 0) {
                while ($post = $result->fetch_assoc()) {
                    $title = htmlspecialchars($post['title']);
                    $author = htmlspecialchars($post['author']);
                    $date = date('M d, Y', strtotime($post['created_at']));
                    echo "
                        <div class='post-card'>
                            <img class='post-thumbnail' src='assets/images/default.jpg' alt='Post image'>
                            <div class='post-card-content'>
                                <h3 class='post-title'>
                                    <a href='post.php?id={$post['id']}'>{$title}</a>
                                </h3>
                                <p class='post-meta'>By <strong>{$author}</strong> • Views: {$post['views']} • {$date}</p>
                                <a class='read-more' href='post.php?id={$post['id']}'>Read more →</a>
                            </div>
                        </div>
                    ";
                }
            } else {
                echo "<p>No posts available yet.</p>";
            }
            ?>
        </div>
    </main>

    <!-- Sidebar phải (Search + Popular) -->
    <aside class="sidebar-right">
        <h3>🔎 Search</h3>
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="q" placeholder="Search posts..." required>
            <button type="submit">Search</button>
        </form>

        <h3>🔥 Popular Posts</h3>
        <ul class="popular-posts">
            <?php
            $popular = $conn->query("
                SELECT id, title 
                FROM posts 
                ORDER BY views DESC 
                LIMIT 5
            ");
            while ($pop = $popular->fetch_assoc()) {
                $popTitle = htmlspecialchars($pop['title']);
                echo "<li><a href='post.php?id={$pop['id']}'>{$popTitle}</a></li>";
            }
            ?>
        </ul>
    </aside>
</div>

<?php include 'includes/footer.php'; ?>