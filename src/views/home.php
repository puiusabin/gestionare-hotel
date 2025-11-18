<div class="home-content">
    <h1>Welcome to Hotel Reservation System</h1>

    <?php if (isLoggedIn()): ?>
        <?php $user = getCurrentUser(); ?>
        <p>Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
        <div class="home-actions">
            <a href="/rooms" class="btn btn-primary">Browse Rooms</a>
        </div>
    <?php else: ?>
        <p>Please login or register to make a reservation.</p>
        <div class="home-actions">
            <a href="/login" class="btn btn-primary">Login</a>
            <a href="/register" class="btn btn-secondary">Register</a>
        </div>
    <?php endif; ?>
</div>
