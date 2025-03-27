<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/dashboard.css">

<div class="dashboard-container">
    <h2>Welcome to your Dashboard</h2>
    <p class="welcome-text">Hello, <strong><?php echo current_user(); ?></strong>! Here are your quick actions:</p>

    <div class="dashboard-cards">
        <a href="post.php?new=1" class="card">
            <i class="fa-solid fa-pen-to-square"></i>
            <h3>Create New Post</h3>
        </a>
        <a href="message.php" class="card">
            <i class="fa-solid fa-envelope"></i>
            <h3>Messages</h3>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>