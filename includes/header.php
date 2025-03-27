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
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <h1 class="logo">WeBlog</h1>
            <p class="subtitle">Share your thoughts, tell your stories, and connect with people everywhere.</p>
            <nav class="navbar">
                <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
                <a href="<?php echo BASE_URL; ?>/dashboard.php">Dashboard</a>
                <?php if (is_logged_in()): ?>
                    <?php
                    $user_id = $_SESSION['user_id'] ?? null;
                    ?>
                    <?php if ($user_id): ?>
                        <a href="<?php echo BASE_URL; ?>/author.php?id=<?php echo $user_id; ?>">Profile</a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php">Logout (<?php echo current_user(); ?>)</a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/auth/login.php">Login</a>
                <?php endif; ?>

            </nav>

        </div>
    </header>
    <main class="content">