<?php
require_once 'admin_check.php';
require_once '../config/database.php';

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<h1>All Users</h1>

<table border="1" cellpadding="10">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['role']; ?></td>

    <td>

    <?php
    if($row['role'] == 'customer'){
    ?>

        <a href="delete_user.php?id=<?php echo $row['id']; ?>">
            Remove
        </a>

    <?php
    }
    else{
        echo "Admin";
    }
    ?>

    </td>
</tr>

<?php } ?>

</table>