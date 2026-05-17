<?php
include 'auth_common.php';
require_login();

$errors = [];
$stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $profile_picture = $user['profile_picture'];

    if ($name === '') $errors['name'] = 'Name is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
    if ($address === '') $errors['address'] = 'Address is required';
    if ($phone === '') $errors['phone'] = 'Phone is required';

    $check = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ? AND id != ?');
    mysqli_stmt_bind_param($check, 'si', $email, $_SESSION['user_id']);
    mysqli_stmt_execute($check);
    if (mysqli_num_rows(mysqli_stmt_get_result($check)) > 0) {
        $errors['email'] = 'Email already used by another account';
    }

    if ($new_password !== '') {
        if (!password_verify($current_password, $user['password_hash'])) {
            $errors['current_password'] = 'Current password is wrong';
        }
        if (strlen($new_password) < 8) {
            $errors['new_password'] = 'New password must be at least 8 characters';
        }
    }

    if (!empty($_FILES['profile_picture']['name'])) {
        $uploaded = upload_profile_picture($_FILES['profile_picture']);
        if ($uploaded === false) {
            $errors['profile_picture'] = 'Only JPG/PNG image under 2MB is allowed';
        } else {
            $profile_picture = $uploaded;
        }
    }

    if (empty($errors)) {
        if ($new_password !== '') {
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = mysqli_prepare($conn, 'UPDATE users SET name=?, email=?, address=?, phone=?, profile_picture=?, password_hash=? WHERE id=?');
            mysqli_stmt_bind_param($update, 'ssssssi', $name, $email, $address, $phone, $profile_picture, $password_hash, $_SESSION['user_id']);
        } else {
            $update = mysqli_prepare($conn, 'UPDATE users SET name=?, email=?, address=?, phone=?, profile_picture=? WHERE id=?');
            mysqli_stmt_bind_param($update, 'sssssi', $name, $email, $address, $phone, $profile_picture, $_SESSION['user_id']);
        }
        mysqli_stmt_execute($update);
        $_SESSION['name'] = $name;
        $_SESSION['message'] = 'Profile updated successfully';
        header('Location: profile.php');
        exit;
    }

    $user['name'] = $name;
    $user['email'] = $email;
    $user['address'] = $address;
    $user['phone'] = $phone;
}

$orders = [];
if (current_role() === 'customer') {
    $order_sql = "SELECT o.*, GROUP_CONCAT(CONCAT(b.title, ' x', oi.quantity) SEPARATOR ', ') AS books
                  FROM orders o
                  LEFT JOIN order_items oi ON oi.order_id = o.id
                  LEFT JOIN books b ON b.id = oi.book_id
                  WHERE o.user_id = ?
                  GROUP BY o.id
                  ORDER BY o.order_date DESC";
    $order_stmt = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param($order_stmt, 'i', $_SESSION['user_id']);
    mysqli_stmt_execute($order_stmt);
    $orders = mysqli_stmt_get_result($order_stmt);
}

$page_title = 'Profile';
include 'header.php';
?>
<div class="auth-grid">
    <div class="auth-card wide">
        <h1>Profile</h1>
        <form method="post" enctype="multipart/form-data" class="auth-form" onsubmit="return validateProfileForm()">
            <label>Name</label>
            <input type="text" name="name" id="profile_name" value="<?php echo clean($user['name']); ?>">
            <small><?php echo $errors['name'] ?? ''; ?></small>

            <label>Email</label>
            <input type="email" name="email" id="profile_email" value="<?php echo clean($user['email']); ?>">
            <small><?php echo $errors['email'] ?? ''; ?></small>

            <label>Address</label>
            <textarea name="address" id="profile_address"><?php echo clean($user['address']); ?></textarea>
            <small><?php echo $errors['address'] ?? ''; ?></small>

            <label>Phone</label>
            <input type="text" name="phone" id="profile_phone" value="<?php echo clean($user['phone']); ?>">
            <small><?php echo $errors['phone'] ?? ''; ?></small>

            <label>Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/jpeg,image/png">
            <small><?php echo $errors['profile_picture'] ?? ''; ?></small>

            <label>Current Password</label>
            <input type="password" name="current_password">
            <small><?php echo $errors['current_password'] ?? ''; ?></small>

            <label>New Password</label>
            <input type="password" name="new_password" id="new_password">
            <small><?php echo $errors['new_password'] ?? ''; ?></small>

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <div class="auth-card wide">
        <h2>Purchase History</h2>
        <?php if (current_role() !== 'customer') { ?>
            <p>Admin account has no purchase history.</p>
        <?php } elseif (mysqli_num_rows($orders) === 0) { ?>
            <p>No purchase yet.</p>
        <?php } else { ?>
            <table class="auth-table">
                <tr><th>Order</th><th>Books</th><th>Total</th><th>Status</th><th>Date</th></tr>
                <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
                    <tr>
                        <td>#<?php echo clean($order['id']); ?></td>
                        <td><?php echo clean($order['books']); ?></td>
                        <td><?php echo clean($order['total_amount']); ?> Tk</td>
                        <td><?php echo clean($order['status']); ?></td>
                        <td><?php echo clean($order['order_date']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>
<?php include 'footer.php'; ?>
