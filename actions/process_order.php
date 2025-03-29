<?php
include_once '../config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['accept_order'])) {
    $order_id = intval($_POST['order_id']);
    $username = $_POST['username'];


    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT user_id, plan_id FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id, $plan_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Lỗi truy vấn: " . mysqli_error($conn);
        header("Location:../admin/orders.php");
        exit;
    }

    if ($user_id && $plan_id) {

        $status_query = "SELECT id FROM status_orders WHERE status = 'completed'";
        $status_result = mysqli_query($conn, $status_query);

        if ($status_row = mysqli_fetch_assoc($status_result)) {
            $status_id = $status_row['id'];
        } else {
            $_SESSION['error'] = "Trạng thái 'completed' không tồn tại.";
            header("Location: ../admin/orders.php");
            exit;
        }

        $update_order = "UPDATE orders SET status_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_order);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $status_id, $order_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Lỗi cập nhật đơn hàng: " . mysqli_error($conn);
            header("Location: ../admin/orders.php");
            exit;
        }

        $insert_subscription = "INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, status_id) 
                                VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 1)";
        $stmt = mysqli_prepare($conn, $insert_subscription);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $plan_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Lỗi khi thêm subscription: " . mysqli_error($conn);
            header("Location: ../admin/orders.php");
            exit;
        }

        $_SESSION['success'] = "Order #$order_id đã được chấp nhận và đăng ký subscription.";
    } else {
        $_SESSION['error'] = "Đơn hàng không hợp lệ.";
    }

    header("Location: ../admin/orders.php");
    exit;
}
