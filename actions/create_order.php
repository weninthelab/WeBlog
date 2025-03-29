<?php
include_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Login first!";
        header("Location: ../auth/login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : 0;
    $payment_method = "Momo QR Payment";

    if (!$plan_id) {
        $_SESSION['error'] = "Error plan!";
        header("Location: ../subscription.php");
        exit();
    }

    $status_query = "SELECT id FROM status_orders WHERE status = 'pending'";
    $status_result = mysqli_query($conn, $status_query);

    if ($status_row = mysqli_fetch_assoc($status_result)) {
        $status_id = $status_row['id'];
    } else {
        $_SESSION['error'] = "Unknow status 'pending'!";
        header("Location: ../order.php");
        exit();
    }

    $insert_order = "INSERT INTO orders (user_id, plan_id, status_id, created_at, payment_method) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = mysqli_prepare($conn, $insert_order);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiis", $user_id, $plan_id, $status_id, $payment_method);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Created order successfully!";
            header("Location: ../subscription.php");
            exit();
        } else {
            $_SESSION['error'] = "Error create order!";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error query: " . mysqli_error($conn);
    }

    header("Location: ../subscription.php");
    exit();
}
