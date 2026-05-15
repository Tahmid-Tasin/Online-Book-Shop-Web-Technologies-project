<section class="panel narrow">
    <h1>Login</h1>
    <?php if (!empty($error)): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
    <form class="form js-auth-form" method="post" action="<?= url('login') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <label>Email
            <input type="email" name="email" value="<?= old($old ?? [], 'email') ?>" required>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label class="inline"><input type="checkbox" name="remember" value="1"> Remember Me</label>
        <button class="button primary" type="submit">Login</button>
        <p class="muted">New customer or admin? <a href="<?= url('register') ?>">Create account</a></p>
    </form>
</section>