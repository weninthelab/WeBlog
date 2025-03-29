<?php
include_once '../config.php';
include_once BASE_PATH . '/includes/header.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $email = trim($_POST['email']);

  
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Username or Email already exists!";
    } else {
        $stmt->close();

        $role_id = 3;
        $avatar_path = "auth/uploads/$username/default_ava.png";

        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role_id, avatar_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $username, $password, $email, $role_id, $avatar_path);

        if ($stmt->execute()) {
       
            $user_folder = "uploads/$username";
            $thumbnail_folder = "$user_folder/thumbnails";

            if (!file_exists($user_folder)) {
                mkdir($user_folder, 0777, true);
            }
            if (!file_exists($thumbnail_folder)) {
                mkdir($thumbnail_folder, 0777, true);
            }


            copy("../assets/images/default_ava.png", "$user_folder/default_ava.png");

            $message = "Register success! <a href='login.php'>Login here</a>";
        } else {
            $message = "Registration failed! Please try again.";
        }
        $stmt->close();
    }
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