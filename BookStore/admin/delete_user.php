<?php
session_start();
require_once '../config/database.php';
header("Content-Type: application/json");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_POST['user_id'])) {
    echo json_encode(["success" => false]);
    exit;
}

$user_id = intval($_POST['user_id']);

// Cascading delete manually if foreign keys aren't set to CASCADE
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");
// If orders should be deleted too:
// mysqli_query($conn, "DELETE FROM order_items WHERE order_id IN (SELECT id FROM orders WHERE user_id=$user_id)");
// mysqli_query($conn, "DELETE FROM orders WHERE user_id=$user_id");

if (mysqli_query($conn, "DELETE FROM users WHERE id=$user_id AND role='customer'")) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>