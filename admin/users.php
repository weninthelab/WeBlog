<?php
include_once '../config.php';
include '../includes/header.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('<div class="alert alert-danger text-center">Access Denied</div>');
}

$current_user = $_SESSION['username']; // Lấy username của user đang đăng nhập

// Truy vấn lấy danh sách user, ngoại trừ admin đang đăng nhập
$sql = "SELECT users.id, users.username, users.email, roles.name 
        FROM users 
        JOIN roles ON users.role_id = roles.id 
        WHERE users.username != ? 
        ORDER BY users.username ASC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("s", $current_user);


$stmt->execute();
$result = $stmt->get_result();

?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Manage Users</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <span class="<?php echo htmlspecialchars($row['name']); ?>">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="delete_user.php?id=<?php echo urlencode($row['id']); ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>
