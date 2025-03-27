<?php
include 'config.php';
include 'includes/header.php';
?>
<link rel="stylesheet" href="assets/css/home.css">

<div class="container layout-3col">
    <!-- Sidebar trái (Top Authors) -->
    <aside class="sidebar-left">
        <h4><i class="fas fa-user-edit"></i></h4>
        <h4>Top Authors</h4>
        <ul class="author-list">
            <?php
            $authorResult = $conn->query("
                SELECT users.id, users.username, COUNT(posts.id) AS post_count
                FROM users
                JOIN posts ON users.id = posts.user_id
                WHERE users.role != 'admin'
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

    <!-- Nội dung chính -->
    <main class="main-content">
        <?php
        $isSearch = isset($_GET['q']) && !empty(trim($_GET['q']));
        $query = '';
        if ($isSearch) {
            $q = $conn->real_escape_string(trim($_GET['q']));
            echo "<h2 class='section-title'><i class='fas fa-search'></i> Search results for: <em>" . htmlspecialchars($q) . "</em></h2>";
            $query = "
                SELECT posts.*, users.username AS author
                FROM posts
                JOIN users ON posts.user_id = users.id
                WHERE posts.title LIKE '%$q%' OR posts.content LIKE '%$q%'
                ORDER BY created_at DESC
            ";
        } else {
            echo "<h2 class='section-title'><i class='fas fa-bolt'></i> New Posts</h2>";
            $query = "
                SELECT posts.*, users.username AS author 
                FROM posts 
                JOIN users ON posts.user_id = users.id 
                ORDER BY created_at DESC
                LIMIT 9
            ";
        }

        $result = $conn->query($query);
        echo "<div class='posts-grid'>";
        if ($result->num_rows > 0) {
            while ($post = $result->fetch_assoc()) {
                $title = htmlspecialchars($post['title']);
                $author = htmlspecialchars($post['author']);
                $date = date('M d, Y', strtotime($post['created_at']));
                echo "
                    <div class='post-card'>
                        <img class='post-thumbnail' src='assets/images/default_thumbnail.jpg' alt='Post image'>
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
            echo $isSearch ? "<p>No results found.</p>" : "<p>No posts available yet.</p>";
        }
        echo "</div>";
        ?>
    </main>

    <!-- Sidebar phải (Search + Popular) -->
    <aside class="sidebar-right">
        <h3><i class="fas fa-search"></i> Search</h3>
        <form action="index.php" method="get" class="search-form">
            <input type="text" name="q" placeholder="Search posts..." required>
            <button type="submit">Search</button>
        </form>

        <h3><i class="fas fa-fire"></i> Popular Posts</h3>
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