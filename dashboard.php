<?php include 'includes/header.php'; ?>
<h2>Welcome to Dashboard!</h2>
<p>Hello, <?php echo current_user(); ?>!</p>
<ul>
    <li><a href="post.php?new=1">Create New Post</a></li>
    <li><a href="message.php">Messages</a></li>
    <li><a href="upload.php">Upload Thumbnail Image</a></li>
</ul>
<?php include 'includes/footer.php'; ?>
