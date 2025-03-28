<?php
include "config.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách các plans
$plans_query = "SELECT * FROM plans";
$plans_result = mysqli_query($conn, $plans_query);

// Lấy subscription hiện tại của user
$subscription_query = "SELECT s.id, p.name, s.start_date, s.end_date, sd.status
                       FROM subscriptions s
                       JOIN plans p ON s.plan_id = p.id
                       JOIN status_descriptions sd ON s.status_id = sd.id
                       WHERE s.user_id = $user_id";
$subscription_result = mysqli_query($conn, $subscription_query);

// Lấy danh sách orders của user
$orders_query = "SELECT o.id, p.name AS plan_name, o.created_at, so.status
                 FROM orders o
                 JOIN plans p ON o.plan_id = p.id
                 JOIN status_orders so ON o.status_id = so.id
                 WHERE o.user_id = $user_id";
$orders_result = mysqli_query($conn, $orders_query);
?>

<style>
    .container {
        max-width: 1200px;
    }

    .card:hover {
        transform: scale(1.05);
        transition: 0.3s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-5">
    <div class="row">
        <!-- Subscription Plans -->
        <div class="col-md-4">
            <h3 class="text-center text-primary">Subscription Plans</h3>
            <?php while ($plan = mysqli_fetch_assoc($plans_result)): ?>
                <div class="card shadow border-0 text-center mb-3">
                    <div class="card-body">
                        <h5 class="card-title text-success fw-bold"><?php echo htmlspecialchars($plan['name']); ?></h5>
                        <p class="card-text">Price: <strong>$<?php echo number_format($plan['price'], 2); ?></strong></p>
                        <p class="card-text">Duration: <?php echo $plan['duration']; ?> days</p>
                        <a href="order.php?plan_id=<?php echo $plan['id']; ?>" class="btn btn-outline-success w-100">Subscribe</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Your Subscriptions -->
        <div class="col-md-4">
            <h3 class="text-center text-primary">Your Subscriptions</h3>
            <table class="table table-hover table-striped mt-3 border">
                <thead class="table-dark">
                    <tr>
                        <th>Plan Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($subscription = mysqli_fetch_assoc($subscription_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($subscription['name']); ?></td>
                            <td><?php echo $subscription['start_date']; ?></td>
                            <td><?php echo $subscription['end_date']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($subscription['status'] == 'Active') ? 'success' : 'secondary'; ?>">
                                    <?php echo htmlspecialchars($subscription['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Your Orders -->
        <div class="col-md-4">
            <h3 class="text-center text-primary">Your Orders</h3>
            <table class="table table-hover table-striped mt-3 border">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Plan Name</th>
                        <th>Created At</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['plan_name']); ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($order['status'] == 'Completed') ? 'success' : 'warning'; ?>">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

header.php:
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