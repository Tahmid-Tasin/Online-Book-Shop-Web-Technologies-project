<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id'])) {
    header("Location: ../Admin1-Auth/login.php");
    exit;
}

if(!isset($_SESSION['order_id'])) {
    die("No order found");
}

$order_id = $_SESSION['order_id'];
$total = $_SESSION['cart_total'];
$pmethod = $_SESSION['payment_method'];

$transaction_id = time();

/* Insert payment */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO payments
    (order_id, amount, payment_method, transaction_id)
    VALUES (?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "idss",
    $order_id,
    $total,
    $pmethod,
    $transaction_id
);

if(mysqli_stmt_execute($stmt))
{
    echo "Payment inserted successfully";
}
else
{
    echo "Error: " . mysqli_error($conn);
}

/* optional cleanup */
unset($_SESSION['order_id']);
unset($_SESSION['payment_method']);

header("Location: ../book/book_list.php");
exit;
?>