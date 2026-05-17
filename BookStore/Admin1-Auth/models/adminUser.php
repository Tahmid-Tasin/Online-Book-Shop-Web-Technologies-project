<?php
require 'includes/db.php';
require 'includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'customer'");
    $stmt->execute([(int) $_POST['delete_id']]);
    $_SESSION['message'] = 'Customer removed.';
    header('Location: admin_users.php');
    exit;
}

$users = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$customers = $pdo->query("SELECT id, name, email FROM users WHERE role = 'customer' ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Users';
require 'includes/header.php';
?>
<section class="grid two">
    <div class="panel">
        <h1>All Registered Users</h1>
        <div class="table-wrap">
            <table>
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th></tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo e($user['name']); ?></td>
                        <td><?php echo e($user['email']); ?></td>
                        <td><?php echo e($user['role']); ?></td>
                        <td><?php echo e($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="panel">
        <h2>Remove Customers</h2>
        <div class="table-wrap">
            <table>
                <tr><th>Name</th><th>Email</th><th>Action</th></tr>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo e($customer['name']); ?></td>
                        <td><?php echo e($customer['email']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Delete this customer?')">
                                <input type="hidden" name="delete_id" value="<?php echo $customer['id']; ?>">
                                <button class="button danger small" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
