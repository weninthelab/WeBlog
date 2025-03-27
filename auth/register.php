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
<style>
    .content {
        background: linear-gradient(270deg, #a94e3b, #c97348, #4a5fa0, #4ea1a1);
        background-size: 800% 800%;
        animation: gradientMove 10s ease infinite;
        padding: 30px 0;
        min-height: 75vh;
        color: #fff;
    }
</style>
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
            <button type="submit" class="transparent-btn">Register</button>

        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</section>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>