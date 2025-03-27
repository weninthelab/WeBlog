<?php
include_once '../config.php'; 
include_once BASE_PATH . '/includes/header.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $email    = $_POST['email'];
    $conn->query("INSERT INTO users (username, password, email, role) VALUES ('$username', '$password', '$email', 'user')");
    $message = "Register success! <a href='login.php'>Login here</a>";
}
?>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/auth.css">
<section class="auth-form">
    <div class="form-container">
        <h2>Create a WeBlog Account</h2>
        <?php if ($message): ?>
            <p class="success-msg"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post">
            <input name="username" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <input name="email" type="email" placeholder="Email" required>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</section>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>