<?php include 'admin_nav.php'; ?>

<div class="page-header">
    <h1>Manage Books (MVC Version)</h1>
    <a href="index.php?controller=admin&action=addBook" class="btn">Add New Book</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo $book['id']; ?></td>
                <td>
                    <?php if(!empty($book['image_path'])): ?>
                        <img src="public/uploads/books/<?php echo $book['image_path']; ?>" width="50" style="border-radius: 4px; object-fit: cover;">
                    <?php else: ?>
                        <span style="color:#999; font-size:12px;">No Image</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo $book['price']; ?> Tk</td>
                <td><?php echo $book['stock']; ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editBook&id=<?php echo $book['id']; ?>" class="btn">Edit</a>
                    <a href="index.php?controller=admin&action=deleteBook&id=<?php echo $book['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="7" style="text-align:center; padding:20px; color:#7A5C3E;">No books found in the database.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>