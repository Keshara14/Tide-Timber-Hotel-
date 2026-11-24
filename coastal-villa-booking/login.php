<?php $pageTitle = 'Login'; require __DIR__ . '/includes/header.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <p class="eyebrow">Welcome back</p>
        <h1>Log in to Tide &amp; Timber</h1>
        <?php display_flash(); ?>
        <form class="form-grid" action="actions/login_process.php" method="post">
            <label>Email address<input type="email" name="email" required></label>
            <label>Password<input type="password" name="password" required></label>
            <button class="button" type="submit">Log in</button>
        </form>
        <p class="form-note">New guest? <a href="register.php">Create an account</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
