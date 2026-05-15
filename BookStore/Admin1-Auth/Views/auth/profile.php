<?php
require 'includes/db.php';
require 'includes/functions.php';
require_login();

$errors = [];
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    $profilePicture = $user['profile_picture'];

    if ($name === '') $errors['name'] = 'Name is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required.';
    if ($address === '') $errors['address'] = 'Address is required.';
    if ($phone === '') $errors['phone'] = 'Phone is required.';

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ?');
    $stmt->execute([$email, $_SESSION['user_id']]);
    if ($stmt->fetch()) $errors['email'] = 'Email already exists.';

    if ($newPassword !== '') {
        if (!password_verify($currentPassword, $user['password_hash'])) $errors['current_password'] = 'Current password is wrong.';
        if (strlen($newPassword) < 8) $errors['password'] = 'New password must be at least 8 characters.';
    }

    if (!empty($_FILES['profile_picture']['name'])) {
        $uploaded = upload_image($_FILES['profile_picture'], 'profiles');
        if ($uploaded === false) {
            $errors['profile_picture'] = 'Only JPG/PNG under 2MB allowed.';
        } else {
            $profilePicture = $uploaded;
        }
    }

    if (!$errors) {
        if ($newPassword !== '') {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, address = ?, phone = ?, profile_picture = ?, password_hash = ? WHERE id = ?');
            $stmt->execute([$name, $email, $address, $phone, $profilePicture, password_hash($newPassword, PASSWORD_DEFAULT), $_SESSION['user_id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, address = ?, phone = ?, profile_picture = ? WHERE id = ?');
            $stmt->execute([$name, $email, $address, $phone, $profilePicture, $_SESSION['user_id']]);
        }
        $_SESSION['name'] = $name;
        $_SESSION['message'] = 'Profile updated.';
        header('Location: profile.php');
        exit;
    }
    $user = array_merge($user, compact('name', 'email', 'address', 'phone'));
}

$orders = [];
if (is_customer()) {
    $stmt = $pdo->prepare('SELECT o.*, GROUP_CONCAT(CONCAT(b.title, " x", oi.quantity) SEPARATOR ", ") AS books FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.id LEFT JOIN books b ON b.id = oi.book_id WHERE o.user_id = ? GROUP BY o.id ORDER BY o.order_date DESC');
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();
}

$pageTitle = 'Profile';
require 'includes/header.php';
?>
<section class="grid two">
    <div class="panel">
        <h1>Profile</h1>
        <form class="form js-profile-form" method="post" enctype="multipart/form-data">
            <label>Name <input name="name" value="<?php echo e($user['name']); ?>" required><small><?php echo e($errors['name'] ?? ''); ?></small></label>
            <label>Email <input type="email" name="email" value="<?php echo e($user['email']); ?>" required><small><?php echo e($errors['email'] ?? ''); ?></small></label>
            <label>Address <textarea name="address" required><?php echo e($user['address']); ?></textarea><small><?php echo e($errors['address'] ?? ''); ?></small></label>
            <label>Phone <input name="phone" value="<?php echo e($user['phone']); ?>" required><small><?php echo e($errors['phone'] ?? ''); ?></small></label>
            <label>Profile Picture <input type="file" name="profile_picture" accept="image/png,image/jpeg"><small><?php echo e($errors['profile_picture'] ?? ''); ?></small></label>
            <label>Current Password <input type="password" name="current_password"><small><?php echo e($errors['current_password'] ?? ''); ?></small></label>
            <label>New Password <input type="password" name="password" minlength="8"><small><?php echo e($errors['password'] ?? ''); ?></small></label>
            <button class="button primary" type="submit">Update Profile</button>
        </form>
    </div>
    <div class="panel">
        <h2>Purchase History</h2>
        <?php if (!is_customer()): ?>
            <p class="muted">Admin accounts do not have customer purchases.</p>
        <?php elseif (!$orders): ?>
            <p class="muted">No purchases yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <tr><th>Order</th><th>Books</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo e($order['id']); ?></td>
                            <td><?php echo e($order['books']); ?></td>
                            <td>৳<?php echo e($order['total_amount']); ?></td>
                            <td><?php echo e($order['status']); ?></td>
                            <td><?php echo e($order['order_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
