<div class="auth-container">
    <h1>Register</h1>

    <form method="POST" action="/register">
        <?php csrfField(); ?>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone (optional)</label>
            <input type="tel" id="phone" name="phone">
        </div>

        <?php recaptchaField(); ?>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <?php recaptchaExecute('register'); ?>

    <p style="margin-top: 20px;">Already have an account? <a href="/login">Login here</a></p>
</div>
