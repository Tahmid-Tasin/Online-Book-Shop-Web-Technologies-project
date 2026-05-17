<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: ../Admin1-Auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$total = $_SESSION['cart_total'];
$pmethod = $_POST['payment_method'];
$status = "pending";

/* Insert order */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO orders (user_id, total_amount, status, payment_method)
     VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "idss",
    $user_id,
    $total,
    $status,
    $pmethod
);

mysqli_stmt_execute($stmt);

/* Get inserted order id */
$order_id = mysqli_insert_id($conn);

/* Save order id + payment method */
$_SESSION['order_id'] = $order_id;
$_SESSION['payment_method'] = $pmethod;

/* Go to payment upload */
header("Location: ./uploadpayment.php");
exit;
?>