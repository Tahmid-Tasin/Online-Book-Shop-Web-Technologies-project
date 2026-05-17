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

$sql = "INSERT INTO orders(user_id,total_amount,status,payment_method)
        VALUES($user_id,$total,'$status','$pmethod')";


$result = mysqli_query($conn, $sql);

header("Location: ./uploadpayment.php");


?>