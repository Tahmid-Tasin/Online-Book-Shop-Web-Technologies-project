<?php

include "../config/database.php";
header('Content-Type: application/json');
$id = intval($_POST['cart_id']);
$change = intval($_POST['change']);

$stmt = mysqli_prepare(
    $conn,
    "UPDATE cart
     SET quantity = quantity + ?
     WHERE id = ?
     AND quantity + ? > 0"
);

mysqli_stmt_bind_param(
    $stmt,
    "iii",
    $change,
    $id,
    $change
);

mysqli_stmt_execute($stmt);
echo json_encode([
    "message"=>"Updated"
]);
?>