<?php
$page_title = $page_title ?? 'BookStore Auth';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo clean($page_title); ?></title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
<nav id="topbar">
    <p id="logo">BookStore.com</p>
    <a class="auth-nav-link" href="../index.php">Home</a>
    <a class="auth-nav-link" href="home.php">Categories</a>
    <?php if (logged_in()) { ?>
        <a class="auth-nav-link" href="profile.php">Profile</a>
        <?php if (current_role() === 'admin') { ?>
            <a class="auth-nav-link" href="../admin/dashboard.php">Admin Panel</a>
        <?php } ?>
        <?php if (current_role() === 'customer') { ?>
            <a class="auth-nav-link" href="../cart/cart.php">Cart</a>
        <?php } ?>
        <a class="auth-nav-link" href="logout.php">Logout</a>
    <?php } else { ?>
        <a class="auth-nav-link" href="login.php">Login</a>
        <a class="auth-nav-link" href="register.php">Register</a>
    <?php } ?>
</nav>
<div class="auth-page">
<?php if (!empty($_SESSION['message'])) { ?>
    <div class="auth-alert success"><?php echo clean($_SESSION['message']); unset($_SESSION['message']); ?></div>
<?php } ?>
