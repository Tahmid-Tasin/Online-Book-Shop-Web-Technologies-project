<?php
include 'auth_common.php';

$error = '';
$email = $_COOKIE['remember_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ?');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if (!empty($_POST['remember'])) {
            setcookie('remember_email', $email, time() + (7 * 24 * 60 * 60), '/');
        }

        if ($user['role'] === 'admin') {
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../book/book_list.php');
        }
        exit;
    } else {
        $error = 'Wrong email or password';
    }
}

$page_title = 'Login';
include 'header.php';
?>
<div class="auth-card">
    <h1>Login</h1>
    <?php if ($error !== '') { ?><div class="auth-alert error"><?php echo clean($error); ?></div><?php } ?>
    <form method="post" class="auth-form" onsubmit="return validateLoginForm()">
        <label>Email</label>
        <input type="email" name="email" id="login_email" value="<?php echo clean($email); ?>">

        <label>Password</label>
        <input type="password" name="password" id="login_password">

        <label class="remember"><input type="checkbox" name="remember" value="1"> Remember Me</label>
        <button type="submit">Login</button>
    </form>
    <p>New here? Sign Up  <a href="register.php">Create account</a></p>
</div>
<?php include 'footer.php'; ?>
