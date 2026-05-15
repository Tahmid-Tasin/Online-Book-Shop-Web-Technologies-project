<section class="panel narrow">
    <h1>Registration</h1>
    <form class="form js-auth-form" method="post" action="<?= url('register') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <label>Name
            <input name="name" value="<?= old($old ?? [], 'name') ?>" required>
            <small><?= e($errors['name'] ?? '') ?></small>
        </label>
        <label>Email
            <input type="email" name="email" value="<?= old($old ?? [], 'email') ?>" required>
            <small><?= e($errors['email'] ?? '') ?></small>
        </label>
        <label>Password
            <input type="password" name="password" minlength="8" required>
            <small><?= e($errors['password'] ?? '') ?></small>
        </label>
        <label>Role
            <select name="role">
                <option value="customer">Customer</option>
                <option value="admin" <?= (($old['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
        </label>
        <label>Address
            <textarea name="address" required><?= e($old['address'] ?? '') ?></textarea>
            <small><?= e($errors['address'] ?? '') ?></small>
        </label>
        <label>Phone
            <input name="phone" value="<?= old($old ?? [], 'phone') ?>" required>
            <small><?= e($errors['phone'] ?? '') ?></small>
        </label>
        <button class="button primary" type="submit">Register</button>
    </form>
</section>