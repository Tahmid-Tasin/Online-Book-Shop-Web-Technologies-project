<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>All Purchase History</h1>
    
    <form method="GET" style="display:inline-block; padding:10px; width:auto; text-align:left; margin:0 auto;">
        <label style="display:inline-block; margin:0 5px;">Status: </label>
        <select name="status" style="width:auto; display:inline-block; margin:0 10px 0 0;">
            <option value="">All</option>
            <option value="pending" <?php if(($_GET['status']??'')=='pending') echo 'selected';?>>Pending</option>
            <option value="confirmed" <?php if(($_GET['status']??'')=='confirmed') echo 'selected';?>>Confirmed</option>
            <option value="shipped" <?php if(($_GET['status']??'')=='shipped') echo 'selected';?>>Shipped</option>
            <option value="delivered" <?php if(($_GET['status']??'')=='delivered') echo 'selected';?>>Delivered</option>
        </select>
        
        <label style="display:inline-block; margin:0 5px;">Date: </label>
        <input type="date" name="order_date" value="<?php echo $_GET['order_date'] ?? ''; ?>" style="width:auto; display:inline-block; margin:0 10px 0 0;">
        
        <button type="submit" class="btn">Filter</button>
        <a href="orders.php" class="btn" style="background:#ddd;">Clear</a>
    </form>
</div>

<table>
    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Books Ordered</th>
        <th>Total Amount</th>
        <th>Method</th>
        <th>Date</th>
        <th>Status</th>
        <th>Change Status</th>
    </tr>

    <?php 
    // Build Dynamic SQL Query based on Filters
    $filter = [];
    if(!empty($_GET['status'])) {
        $stat = mysqli_real_escape_string($conn, $_GET['status']);
        $filter[] = "o.status = '$stat'";
    }
    if(!empty($_GET['order_date'])) {
        $date = mysqli_real_escape_string($conn, $_GET['order_date']);
        $filter[] = "DATE(o.order_date) = '$date'";
    }
    
    $whereClause = count($filter) > 0 ? "WHERE " . implode(" AND ", $filter) : "";

    $query = "
        SELECT o.id, u.name, o.total_amount, o.payment_method, o.status, o.order_date,
               GROUP_CONCAT(CONCAT(b.title, ' (x', oi.quantity, ')') SEPARATOR '<br>') as books
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN books b ON oi.book_id = b.id
        $whereClause
        GROUP BY o.id
        ORDER BY o.order_date DESC
    ";
    
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo $row['books'] ?: 'N/A'; ?></td>
        <td><?php echo $row['total_amount']; ?> Tk</td>
        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
        <td><?php echo date('M d, Y', strtotime($row['order_date'])); ?></td>
        <td id="status-<?php echo $row['id']; ?>"><strong><?php echo ucfirst($row['status']); ?></strong></td>
        <td>
            <select onchange="updateStatus(<?php echo $row['id']; ?>, this.value)" style="margin:0; width:110px;">
                <option value="">Update...</option>
                <option value="confirmed">Confirm</option>
                <option value="shipped">Ship</option>
                <option value="delivered">Deliver</option>
            </select>
        </td>
    </tr>
    <?php } ?>
</table>

<script>
function updateStatus(orderId, newStatus) {
    if(!newStatus) return;
    let formData = new FormData();
    formData.append("order_id", orderId);
    formData.append("status", newStatus);

    fetch("update_status.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById("status-" + orderId).innerText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        } else {
            alert("Update failed");
        }
    });
}
</script>
</body>
</html>