<?php
include '../config/database.php';

//Total Books
$books_query = "SELECT COUNT(*) AS total_books FROM books";
$books_result = mysqli_query($conn, $books_query);
$books = mysqli_fetch_assoc($books_result);

//Total Users
$users_query = "SELECT COUNT(*) AS total_users FROM users";
$users_result = mysqli_query($conn, $users_query);
$users = mysqli_fetch_assoc($users_result);

//Total Orders
$orders_query = "SELECT COUNT(*) AS total_orders FROM orders";
$orders_result = mysqli_query($conn, $orders_query);
$orders = mysqli_fetch_assoc($orders_result);

//Total Revenue
$revenue_query = "SELECT SUM(total_amount) AS total_revenue FROM orders";
$revenue_result = mysqli_query($conn, $revenue_query);
$revenue = mysqli_fetch_assoc($revenue_result);
?>

<h1>Admin Dashboard</h1>

<div style="display:flex; gap:20px;">

    <div style="border:1px solid black; padding:20px;">
        <h2>Total Books</h2>
        <p><?php echo $books['total_books']; ?></p>
    </div>

    <div style="border:1px solid black; padding:20px;">
        <h2>Total Users</h2>
        <p><?php echo $users['total_users']; ?></p>
    </div>

    <div style="border:1px solid black; padding:20px;">
        <h2>Total Orders</h2>
        <p><?php echo $orders['total_orders']; ?></p>
    </div>

    <div style="border:1px solid black; padding:20px;">
        <h2>Total Revenue</h2>
        <p>$<?php echo $revenue['total_revenue']; ?></p>
    </div>

</div>

<a href="orders.php">View Orders</a>
<a href="books.php">Books</a>
<a href="users.php">Users</a>