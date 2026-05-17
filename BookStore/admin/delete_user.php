<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_POST['user_id'])) {
    echo json_encode(["success" => false]);
    exit;
}

$user_id = intval($_POST['user_id']);

// 1. Delete user's active cart items
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");

// 2. Delete the individual book items from the user's past orders
mysqli_query($conn, "DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id=$user_id)");

// 3. Delete the user's actual orders and payments
mysqli_query($conn, "DELETE FROM payments WHERE order_id IN (SELECT id FROM orders WHERE user_id=$user_id)");
mysqli_query($conn, "DELETE FROM orders WHERE user_id=$user_id");

// 4. Finally, delete the customer account
if (mysqli_query($conn, "DELETE FROM users WHERE id=$user_id AND role='customer'")) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($conn)]);
}
?>