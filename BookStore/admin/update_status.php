<?php
session_start();
include "../config/database.php";

header("Content-Type: application/json");

if(
    !isset($_POST['order_id']) ||
    !isset($_POST['status'])
){
    echo json_encode([
        "success" => false
    ]);
    exit;
}

$order_id = intval($_POST['order_id']);
$status = $_POST['status'];

$allowed = [
    "confirmed",
    "shipped",
    "delivered"
];

if(!in_array($status, $allowed)){
    echo json_encode([
        "success" => false
    ]);
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE orders
     SET status=?
     WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "si",
    $status,
    $order_id
);

if(mysqli_stmt_execute($stmt)){
    echo json_encode([
        "success" => true
    ]);
}
else{
    echo json_encode([
        "success" => false
    ]);
}
?>