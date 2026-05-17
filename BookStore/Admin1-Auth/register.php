<?php
include 'auth_common.php';

$errors = [];
$name = '';
$email = '';
$role = 'customer';
$address = '';
$phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = ($_POST['role'] ?? 'customer') === 'admin' ? 'admin' : 'customer';
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '') $errors['name'] = 'Name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
    if (strlen($password) < 8) $errors['password'] = 'Password must be at least 8 characters';
    if ($address === '') $errors['address'] = 'Address is required';
    if ($phone === '') $errors['phone'] = 'Phone is required';

    $check = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ?');
    mysqli_stmt_bind_param($check, 's', $email);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = 'Email already exists';
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conn, 'INSERT INTO users(name, email, password_hash, role, address, phone) VALUES(?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'ssssss', $name, $email, $password_hash, $role, $address, $phone);
        mysqli_stmt_execute($stmt);

        $_SESSION['message'] = 'Registration successful. Please login.';
        header('Location: login.php');
        exit;
    }
}

$page_title = 'Register';
include 'header.php';
?>
<div class="auth-card">
    <h1>Registration</h1>
    <form method="post" class="auth-form" onsubmit="return validateRegisterForm()">
        <label>Name</label>
        <input type="text" name="name" id="name" value="<?php echo clean($name); ?>">
        <small><?php echo $errors['name'] ?? ''; ?></small>

        <label>Email</label>
        <input type="email" name="email" id="email" value="<?php echo clean($email); ?>">
        <small><?php echo $errors['email'] ?? ''; ?></small>

        <label>Password</label>
        <input type="password" name="password" id="password">
        <small><?php echo $errors['password'] ?? ''; ?></small>

        <label>Role</label>
        <select name="role">
            <option value="customer">Customer</option>
            <option value="admin" <?php if ($role === 'admin') echo 'selected'; ?>>Admin</option>
        </select>

        <label>Address</label>
        <textarea name="address" id="address"><?php echo clean($address); ?></textarea>
        <small><?php echo $errors['address'] ?? ''; ?></small>

        <label>Phone</label>
        <input type="text" name="phone" id="phone" value="<?php echo clean($phone); ?>">
        <small><?php echo $errors['phone'] ?? ''; ?></small>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<?php include 'footer.php'; ?>
