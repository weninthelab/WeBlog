<?php
include_once __DIR__ . '/../config.php';
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WeBlog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="shortcut icon" href="/assets/images/wb.ico" type="image/x-icon">
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <h1 class="logo">WeBlog</h1>
            <p class="subtitle">Share your thoughts, tell your stories, and connect with people everywhere.</p>
            <nav class="navbar">

                <?php
                if (isset($_SESSION['user_id'])):
                    $role = $_SESSION['role'] ?? 'user';
                ?>
                    <?php if ($role !== 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>/index.php">HOME</a>
                    <?php endif; ?>
                    <?php if ($role === 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>/admin/index.php">DASHBOARD</a>
                        <a href="<?php echo BASE_URL; ?>/admin/users.php">USER</a>
                        <a href="<?php echo BASE_URL; ?>/admin/posts.php">POST</a>
                        <a href="<?php echo BASE_URL; ?>/admin/subscriptions.php">SUBSCRIPTION</a>
                        <a href="<?php echo BASE_URL; ?>/admin/orders.php">ORDER</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/dashboard.php">DASHBOARD</a>
                    <?php endif; ?>
                    <?php if ($role !== 'admin'): ?>
                        <a href="<?php echo BASE_URL; ?>/author.php?id=<?php echo $_SESSION['user_id']; ?>">PROFILE</a>
                        <a href="<?php echo BASE_URL; ?>/subscription.php">SUBSCRIPTION</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php">LOGOUT (<?php echo $_SESSION['username']; ?>)</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/auth/login.php">LOGIN</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="content">