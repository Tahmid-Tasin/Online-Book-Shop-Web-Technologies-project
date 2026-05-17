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
$transaction_id = time();

/* STEP 1: Get latest order */
$query = "SELECT order_id FROM orders WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

/* STEP 2: check if order exists */
if (!$row) {
    die("No order found for this user");
}

$order_id = $row['id'];

/* STEP 3: insert payment */
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO payments (order_id, amount, payment_method, transaction_id)
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

if (mysqli_stmt_execute($stmt)) {
    echo "Payment inserted successfully";
} else {
    echo "Error: " . mysqli_error($conn);
}

header("Location: ../book_list.php");
exit;
?>