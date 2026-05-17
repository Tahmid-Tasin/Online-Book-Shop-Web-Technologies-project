<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>All Registered Users</h1>
    <p>View and manage all accounts.</p>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registration Date</th>
        <th>Action</th>
    </tr>
    <?php
    $query = "SELECT * FROM users ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    while($row = mysqli_fetch_assoc($result)) { ?>
        <tr id="user_row_<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><strong><?php echo ucfirst($row['role']); ?></strong></td>
            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
            <td>
                <?php if($row['role'] == 'customer'){ ?>
                    <button class="btn btn-danger" onclick="deleteUser(<?php echo $row['id']; ?>)">Remove Customer</button>
                <?php } else { ?>
                    <span style="color: #7A5C3E; font-weight: bold;">Admin</span>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

<script>
function deleteUser(id) {
    if(confirm("Are you sure you want to permanently remove this customer and all their data (orders, cart, etc)?")) {
        let formData = new FormData();
        formData.append("user_id", id);

        fetch("delete_user.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('user_row_' + id).style.display = 'none';
            } else {
                alert("Failed to delete user.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
    }
}
</script>
</body>
</html>