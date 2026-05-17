<html>

<?php
require 'includes/db.php';
require 'includes/functions.php';

$error = '';
$email = $_COOKIE['remember_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        if (!empty($_POST['remember'])) {
            setcookie('remember_email', $email, time() + 604800, '/');
        }
        header('Location: index.php');
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

$pageTitle = 'Login';
require 'includes/header.php';
?>

<section class="panel narrow">
    <h1>Login</h1>
    <?php if ($error): ?><div class="alert danger"><?php echo e($error); ?></div><?php endif; ?>
    <form class="form js-auth-form" method="post">
        <label>Email <input type="email" name="email" value="<?php echo e($email); ?>" required></label>
        <label>Password <input type="password" name="password" required></label>
        <label class="inline"><input type="checkbox" name="remember" value="1"> Remember Me</label>
        <button class="button primary" type="submit">Login</button>
        <p class="muted">New customer or admin? <a href="register.php">Create account</a></p>
    </form>
</section>


</html>

<?php require 'includes/footer.php'; ?>

