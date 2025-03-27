<?php
include 'includes/header.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("UPDATE posts SET views = views + 1 WHERE id = $id");
    $post = $conn->query("SELECT * FROM posts WHERE id = $id")->fetch_assoc();
    echo "<h2>{$post['title']}</h2>";
    echo "<p>{$post['content']}</p>";
    echo "<h3>Comments</h3>";
    $result = $conn->query("SELECT * FROM comments WHERE post_id = $id");
    while ($cmt = $result->fetch_assoc()) {
        echo "<p>{$cmt['comment']}</p>";
    }
?>
    <form method="post" action="actions/comment.php">
        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
        <textarea name="comment"></textarea><br>
        <button type="submit">Comment</button>
    </form>
<?php
}
if (isset($_GET['new']) && is_logged_in()) {
?>
    <h2>Create New Post</h2>
    <form method="post">
        Title: <input name="title"><br>
        Content:<br>
        <textarea name="content"></textarea><br>
        <button type="submit">Post</button>
    </form>
<?php
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_logged_in()) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $uid = $_SESSION['user_id'];
    $conn->query("INSERT INTO posts (user_id, title, content) VALUES ($uid, '$title', '$content')");
    echo "Post created!";
}
include 'includes/footer.php';
?>