<?php
session_start();
include "../config/database.php";

if(!isset($_SESSION['user_id']))
{
    header("Location: ../Admin1-Auth/login.php");
    exit;
} 

$userid = $_SESSION['user_id'];
$total = $_SESSION['cart_total'];



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout & Payment</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body><h3>Total: <?php echo $total; ?></h3>
<form action="./ordersupload.php" method="POST" onsubmit="return validateForm()">
<input type="hidden" name="total" value="<?php echo $total; ?>">
    <label for="payment">Select Payment Method:</label>
    <select id="payment" name="payment_method" required>
        <option value="">-- Choose Payment Method --</option>
        <option value="cash_on_delivery">Cash on Delivery</option>
        <option value="bkash">bKash</option>
        <option value="nagad">Nagad</option>
        <option value="rocket">Rocket</option>
        <option value="card">Credit/Debit Card</option>
        <option value="paypal">PayPal</option>
    </select>

    <br><br>

    <label>Address:</label>
    <input type="text" id="address" name="address">

    <br><br>

    <input type="submit" value="Submit Order">

</form>

<script src="../script.js"></script>
</body>
</html>