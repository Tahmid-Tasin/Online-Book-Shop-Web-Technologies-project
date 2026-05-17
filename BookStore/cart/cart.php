<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: ../Admin1-Auth/login.php");
    exit;
} 
include "../config/database.php";

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare(
    $conn,
    "SELECT cart.id,
            books.title,
            books.price,
            cart.quantity
     FROM cart
     JOIN books
     ON cart.book_id=books.id
     WHERE cart.user_id=?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>My Cart</h1>
<?php if(mysqli_num_rows($result)==0){ ?>
    <h2>Your cart is empty</h2>
<?php } ?>

<?php while($row=mysqli_fetch_assoc($result))
{ 
$subtotal = $row['price'] * $row['quantity'];
$total += $subtotal;
?>

<div class="card">
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p>Price: <?php echo $row['price']; ?> Tk</p>
    <p>
        Quantity:
        <button onclick="updateCart(<?php echo $row['id']; ?>,-1)">-</button>
        <?php echo $row['quantity']; ?>
        <button onclick="updateCart(<?php echo $row['id']; ?>,1)">+</button>
    </p>
    <p>Subtotal: <?php echo $subtotal; ?> Tk</p>
    <button onclick="removeCart(<?php echo $row['id']; ?>)">Remove</button>
</div>
<?php 
} ?>

<h2>Total: <?php echo $total; ?> Tk</h2>
<script src="../script.js"></script>
</body>
</html>
