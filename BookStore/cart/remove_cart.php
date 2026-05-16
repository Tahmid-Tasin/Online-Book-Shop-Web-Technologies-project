<?php

include "../config/database.php";

header('Content-Type: application/json');
$id = intval($_POST['cart_id']);
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM cart WHERE id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);
echo json_encode([
    "message"=>"Removed"
]);
?>