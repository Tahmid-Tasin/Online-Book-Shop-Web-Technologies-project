<?php
session_start();
include '../config/database.php';

$userid = $_SESSION['user_id'];

$query = "
SELECT 
    users.name,
    orders.id,
    orders.total_amount,
    orders.payment_method,
    orders.status,
    orders.order_date
FROM orders
INNER JOIN users
ON orders.user_id = users.id
WHERE orders.user_id = $userid
ORDER BY orders.order_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders</title>
</head>
<body>



<table border="1" cellpadding="10">
<tr>

    <th>Customer Name</th>
    <th>Total Amount</th>
    <th>Payment Method</th>
    <th>Status</th>
    <th>Order Date</th>

</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>


    <td><?php echo htmlspecialchars($row['name']); ?></td>

    <td><?php echo $row['total_amount']; ?> Tk</td>

    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>

    <td id="status-<?php echo $row['id']; ?>">
        <?php echo htmlspecialchars($row['status']); ?>
    </td>

    <td><?php echo $row['order_date']; ?></td>




<?php } ?>

</table>

<script src="../script.js"></script>

</body>
</html>