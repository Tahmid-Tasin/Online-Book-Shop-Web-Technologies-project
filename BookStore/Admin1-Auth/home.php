<?php
include 'auth_common.php';

$categories = mysqli_query($conn, 'SELECT * FROM categories ORDER BY name');
$category_id = intval($_GET['category_id'] ?? 0);

if ($category_id > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id=b.category_id WHERE b.category_id=? ORDER BY b.title');
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
    mysqli_stmt_execute($stmt);
    $books = mysqli_stmt_get_result($stmt);
} else {
    $books = mysqli_query($conn, 'SELECT b.*, c.name AS category_name FROM books b LEFT JOIN categories c ON c.id=b.category_id ORDER BY b.created_at DESC LIMIT 8');
}

$page_title = 'Home - Categories';
include 'header.php';
?>
<div class="auth-card wide">
    <h1>Home Page With Categories</h1>
    <p>Browse books by category and view basic book information.</p>
    <div class="category-row">
        <a href="home.php">All</a>
        <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
            <a href="home.php?category_id=<?php echo $cat['id']; ?>"><?php echo clean($cat['name']); ?></a>
        <?php } ?>
    </div>
</div>

<div class="auth-book-grid">
    <?php while ($book = mysqli_fetch_assoc($books)) { ?>
        <div class="auth-book-card">
            <h2><?php echo clean($book['title']); ?></h2>
            <p><b>Author:</b> <?php echo clean($book['author']); ?></p>
            <p><b>Category:</b> <?php echo clean($book['category_name']); ?></p>
            <p><?php echo clean(substr($book['description'], 0, 120)); ?></p>
            <p><b><?php echo clean($book['price']); ?> Tk</b></p>
            <a href="../book/book_details.php?id=<?php echo $book['id']; ?>">View Details</a>
        </div>
    <?php } ?>
</div>
<?php include 'footer.php'; ?>
