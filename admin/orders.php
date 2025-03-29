<?php
include_once '../config.php';
include '../includes/header.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}
// Truy vấn danh sách đơn hàng
$query = "SELECT 
    orders.id, 
    users.username, 
    plans.price AS amount, 
    status_orders.status AS status, 
    orders.payment_method, 
    orders.created_at 
FROM orders 
JOIN users ON orders.user_id = users.id 
JOIN plans ON orders.plan_id = plans.id 
JOIN status_orders ON orders.status_id = status_orders.id";

$result = mysqli_query($conn, $query);
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Orders</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Manage Orders</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Username</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo number_format($row['amount'], 2); ?> USD</td>
                        <td>
                            <span class="status-<?php echo htmlspecialchars(strtolower($row['status'])); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>