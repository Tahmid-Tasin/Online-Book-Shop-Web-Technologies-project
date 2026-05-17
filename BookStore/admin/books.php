<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>Manage Books</h1>
    <a href="add_book.php" class="btn">Add New Book</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td>
                <?php if($row['image_path']){ ?>
                    <img src="../public/uploads/books/<?php echo $row['image_path']; ?>" width="50">
                <?php } ?>
            </td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <a href="edit_book.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
                <a href="delete_book.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Delete this book?');">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>