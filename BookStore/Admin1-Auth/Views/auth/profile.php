<section class="grid two">
    <div class="panel">
        <h1>Profile</h1>
        <form class="form js-profile-form" method="post" action="<?= url('profile') ?>" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <label>Name
                <input name="name" value="<?= e($user['name'] ?? '') ?>" required>
                <small><?= e($errors['name'] ?? '') ?></small>
            </label>
            <label>Email
                <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required>
                <small><?= e($errors['email'] ?? '') ?></small>
            </label>
            <label>Address
                <textarea name="address" required><?= e($user['address'] ?? '') ?></textarea>
                <small><?= e($errors['address'] ?? '') ?></small>
            </label>
            <label>Phone
                <input name="phone" value="<?= e($user['phone'] ?? '') ?>" required>
                <small><?= e($errors['phone'] ?? '') ?></small>
            </label>
            <label>Profile Picture
                <input type="file" name="profile_picture" accept="image/png,image/jpeg">
                <small><?= e($errors['profile_picture'] ?? '') ?></small>
            </label>
            <label>Current Password
                <input type="password" name="current_password">
                <small><?= e($errors['current_password'] ?? '') ?></small>
            </label>
            <label>New Password
                <input type="password" name="password" minlength="8">
                <small><?= e($errors['password'] ?? '') ?></small>
            </label>
            <button class="button primary" type="submit">Update Profile</button>
        </form>
    </div>
    <div class="panel">
        <h2>Purchase History</h2>
        <?php if (Auth::role() !== 'customer'): ?>
            <p class="muted">Admin accounts do not have customer purchases.</p>
        <?php elseif (!$orders): ?>
            <p class="muted">No purchases yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <tr><th>Order</th><th>Books</th><th>Total</th><th>Status</th><th>Date</th></tr>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= e((string) $order['id']) ?></td>
                            <td><?= e($order['books']) ?></td>
                            <td>৳<?= e((string) $order['total_amount']) ?></td>
                            <td><?= e($order['status']) ?></td>
                            <td><?= e($order['order_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>