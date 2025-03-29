<?php
include_once '../config.php';
include '../includes/header.php';

if ($_SESSION['role'] !== 'admin') die('<div class="alert alert-danger text-center">Access Denied</div>');

$query = "SELECT posts.id, posts.title, posts.created_at, posts.views, posts.premium, users.username, 
                 (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count
          FROM posts 
          JOIN users ON posts.user_id = users.id";
$result = mysqli_query($conn, $query);
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Posts</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/admin.css">
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Manage Posts</h2>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Views</th>
                    <th>Comments</th>
                    <th>Premium</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($row['views']); ?></td>
                        <td><?php echo htmlspecialchars($row['comment_count']); ?></td>
                        <td>
                            <?php echo $row['premium'] ? "<span class='premium'>Premium</span>" : "Normal"; ?>
                        </td>

                        <td>
                            <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../includes/footer.php'; ?>