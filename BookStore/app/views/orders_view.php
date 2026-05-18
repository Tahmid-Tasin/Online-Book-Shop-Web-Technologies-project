<?php require_once dirname(__DIR__) . '/views/admin_nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History Workflow</title>
<link rel="stylesheet" href="/BookStore/public/css/admin-style.css"><body>

<div class="page-header">
    <h1>All Purchase History (MVC)</h1>
    
    <form method="GET" action="/Online-Book-Shop-Web-Technologies-project/BookStore/admin.php" style="display:inline-block; padding:10px; width:auto; text-align:left; margin:0 auto;">
        
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="orders">

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
        <a href="/Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=orders" class="btn" style="background:#ddd; color:#333; text-decoration:none;">Clear</a>
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

    <?php if (!empty($ordersList)): ?>
        <?php foreach ($ordersList as $order): ?>
        <tr>
            <td><?php echo $order['id']; ?></td>
            <td><?php echo htmlspecialchars($order['name']); ?></td>
            <td><?php echo (!empty($order['books'])) ? $order['books'] : '<em style="color:#999;">No items</em>'; ?></td>
            <td><?php echo $order['total_amount']; ?> Tk</td>
            <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
            <td id="status-<?php echo $order['id']; ?>"><strong><?php echo ucfirst($order['status']); ?></strong></td>
            <td>
                <select onchange="updateStatus(<?php echo $order['id']; ?>, this.value)" style="margin:0; width:110px;">
                    <option value="">Update...</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirm</option>
                    <option value="shipped">Ship</option>
                    <option value="delivered">Deliver</option>
                </select>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8" style="text-align:center; color:#7A5C3E; padding: 20px;">No purchase records found matching your criteria.</td>
        </tr>
    <?php endif; ?>
</table>

<script>
function updateStatus(orderId, newStatus) {
    if(!newStatus) return;

    const payload = {
        id: parseInt(orderId),
        status: newStatus
    };

    fetch("/Online-Book-Shop-Web-Technologies-project/BookStore/admin.php?controller=admin&action=updateOrderStatus", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.getElementById("status-" + orderId).innerHTML = "<strong>" + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + "</strong>";
        } else {
            alert("Update failed inside database layer processing.");
        }
    })
    .catch(err => {
        console.error("AJAX Error:", err);
        alert("An error occurred during transaction runtime updates.");
    });
}
</script>
</body>
</html>