<div class="auth-container">
    <h1>Login</h1>

    <form method="POST" action="/login">
        <?php csrfField(); ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <?php recaptchaField(); ?>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <?php recaptchaExecute('login'); ?>

    <p style="margin-top: 20px;">Don't have an account? <a href="/register">Register here</a></p>
</div>