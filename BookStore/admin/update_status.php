<?php
include '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $allowed = ['pending', 'confirmed', 'shipped', 'delivered'];

    if (!in_array($status, $allowed)) {
        die("Invalid status");
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE orders SET status=? WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Status updated successfully";
    } else {
        echo "Database update failed";
    }
}
?>