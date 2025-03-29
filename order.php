<?php
include "config.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$plan_id = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;
$plan_query = "SELECT * FROM plans WHERE id = $plan_id";
$plan_result = mysqli_query($conn, $plan_query);
$plan = mysqli_fetch_assoc($plan_result);

if (!$plan) {
    echo "<div class='alert alert-danger'>Plan không tồn tại!</div>";
    include "includes/footer.php";
    exit();
}

$qr_code_url = "assets/images/momoqr.jpg";
?>

<style>
    .container {
        max-width: 800px;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container mt-5">
    <h3 class="text-center text-primary">Order Confirmation</h3>
    <div class="card shadow p-4 border-0">
        <h4 class="text-success text-center">Plan: <?php echo htmlspecialchars($plan['name']); ?></h4>
        <p class="text-center">Price: <strong>$<?php echo number_format($plan['price'], 2); ?></strong></p>
        <p class="text-center">Duration: <?php echo $plan['duration']; ?> days</p>

        <div class="text-center mt-4">
            <h5>QR Code payment</h5>
            <img src="<?php echo $qr_code_url; ?>" alt="MoMo QR Code" class="img-fluid" style="width: 450px;">
            <br>
            <form action="actions/create_order.php" method="POST">
                <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
                <button type="submit" class="btn btn-primary mt-3">Checkout</button>
            </form>
        </div>


    </div>
</div>

<?php include "includes/footer.php"; ?>