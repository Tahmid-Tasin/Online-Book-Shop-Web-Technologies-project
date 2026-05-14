<?php
include '../config/database.php';
require_once '../config/database.php';

$query = "
SELECT 
    orders.id,
    users.name,
    orders.total_amount,
    orders.payment_method,
    orders.status,
    orders.order_date

FROM orders

INNER JOIN users
ON orders.user_id = users.id

ORDER BY orders.order_date DESC
";

$result = mysqli_query($conn, $query);
?>

<h1>All Orders</h1>

<table border="1" cellpadding="10">

<tr>
    <th>Order ID</th>
    <th>Customer Name</th>
    <th>Total Amount</th>
    <th>Payment Method</th>
    <th>Status</th>
    <th>Order Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>

    <td><?php echo $row['id']; ?></td>

    <td><?php echo $row['name']; ?></td>

    <td>$<?php echo $row['total_amount']; ?></td>

    <td><?php echo $row['payment_method']; ?></td>

    <td><?php echo $row['status']; ?></td>

    <td><?php echo $row['order_date']; ?></td>

</tr>

<?php } ?>

</table>