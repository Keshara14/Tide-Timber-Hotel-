<?php $pageTitle = 'Register'; require __DIR__ . '/includes/header.php'; ?>
<section class="auth-section">
    <div class="auth-card">
        <p class="eyebrow">Create an account</p>
        <h1>Start planning your stay</h1>
        <?php display_flash(); ?>
        <form id="register-form" class="form-grid" action="actions/register_process.php" method="post">
            <label>Full name<input type="text" name="name" required></label>
            <label>Email address<input type="email" name="email" required></label>
            <label>Contact number<input type="tel" name="contact_number" required></label>
            <label>Password<input type="password" name="password" minlength="6" required></label>
            <label>Confirm password<input type="password" name="confirm_password" minlength="6" required></label>
            <button class="button" type="submit">Create account</button>
        </form>
        <p class="form-note">Already registered? <a href="login.php">Log in</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
