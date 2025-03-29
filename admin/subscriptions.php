<?php
include_once '../config.php';
include '../includes/header.php';

if ($_SESSION['role'] !== 'admin') {
    die('<div class="alert alert-danger text-center">Access Denied</div>');
}
$result = mysqli_query($conn, "
    SELECT 
        subscriptions.id, 
        users.username, 
        plans.name AS plan, 
        plans.price, 
        subscriptions.start_date, 
        subscriptions.end_date, 
        status_descriptions.status 
    FROM subscriptions 
    INNER JOIN users ON subscriptions.user_id = users.id 
    INNER JOIN plans ON subscriptions.plan_id = plans.id 
    INNER JOIN status_descriptions ON subscriptions.status_id = status_descriptions.id
");

?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Subscriptions</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Manage Subscriptions</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Plan</th>
                    <th>Price</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['plan']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                        <td>
                            <span class="status-<?php echo htmlspecialchars(strtolower($row['status'])); ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>