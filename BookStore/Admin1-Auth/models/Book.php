<?php
require 'includes/db.php';
require 'includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT b.*, c.name AS category_name FROM books b JOIN categories c ON c.id = b.category_id WHERE b.id = ?');
$stmt->execute([$id]);
$book = $stmt->fetch();
if (!$book) die('Book not found.');

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_customer();
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    if ($quantity > (int) $book['stock']) {
        $message = 'Stock not available.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM cart WHERE user_id = ? AND book_id = ?');
        $stmt->execute([$_SESSION['user_id'], $id]);
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare('UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND book_id = ?');
            $stmt->execute([$quantity, $_SESSION['user_id'], $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $id, $quantity]);
        }
        $message = 'Added to cart.';
    }
}

$pageTitle = $book['title'];
require 'includes/header.php';
?>
<section class="panel book-detail">
    <div class="book-cover large">
        <?php if (!empty($book['image_path'])): ?>
            <img src="<?php echo e($book['image_path']); ?>" alt="<?php echo e($book['title']); ?>">
        <?php else: ?>
            <span><?php echo e(substr($book['title'], 0, 1)); ?></span>
        <?php endif; ?>
    </div>
    <div>
        <p class="eyebrow"><?php echo e($book['category_name']); ?></p>
        <h1><?php echo e($book['title']); ?></h1>
        <p class="muted">By <?php echo e($book['author']); ?></p>
        <p><?php echo e($book['description']); ?></p>
        <p><strong>Price:</strong> ৳<?php echo e($book['price']); ?></p>
        <p><strong>Stock:</strong> <?php echo (int) $book['stock'] > 0 ? e($book['stock']) : 'Out of stock'; ?></p>
        <?php if ($message): ?><div class="alert success"><?php echo e($message); ?></div><?php endif; ?>
        <?php if (is_customer()): ?>
            <form class="form compact js-cart-form" method="post">
                <label>Quantity <input type="number" name="quantity" value="1" min="1" max="<?php echo e($book['stock']); ?>"></label>
                <button class="button primary" type="submit">Add to Cart</button>
            </form>
        <?php else: ?>
            <p><a class="button primary" href="login.php">Login as customer to add to cart</a></p>
        <?php endif; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
