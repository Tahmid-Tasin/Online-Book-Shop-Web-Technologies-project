<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>All Registered Users (MVC)</h1>
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
    <?php foreach ($usersList as $user): ?>
        <tr id="user_row_<?php echo $user['id']; ?>">
            <td><?php echo $user['id']; ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><strong><?php echo ucfirst($user['role']); ?></strong></td>
            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
            <td>
                <?php if($user['role'] == 'customer'){ ?>
                    <button class="btn btn-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">Remove Customer</button>
                <?php } else { ?>
                    <span style="color: #7A5C3E; font-weight: bold;">Admin</span>
                <?php } ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
function deleteUser(id) {
    if(confirm("Are you sure you want to permanently remove this customer and all their data?")) {
        let formData = new FormData();
        formData.append("user_id", id);

        // Notice the URL! We send the request through the Router now.
        fetch("index.php?controller=admin&action=deleteUser", {
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