<?php
include_once '../config.php';
include_once BASE_PATH . '/includes/header.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Không hash để demo vulnerable
    $result = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ' . BASE_URL . '/index.php');
    } else {
        $error = "Invalid login. Please try again.";
    }
}
?>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/auth.css">
<section class="auth-form">
    <div class="form-container">
        <h2>Login to WeBlog</h2>
        <?php if ($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <input name="username" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register now</a></p>
    </div>
</section>

<?php include_once BASE_PATH . '/includes/footer.php';
?>