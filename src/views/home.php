<div class="row">
    <div class="col-md-8">
        <div class="home-content mb-4">
            <h1>Welcome to Hotel Reservation System</h1>

            <?php if (isLoggedIn()): ?>
                <?php $user = getCurrentUser(); ?>
                <p class="lead">Welcome back, <?php echo htmlspecialchars($user['first_name']); ?>!</p>
                <div class="home-actions mt-3">
                    <a href="/rooms" class="btn btn-primary">Browse Rooms</a>
                </div>
            <?php else: ?>
                <p class="lead">Please login or register to make a reservation.</p>
                <div class="home-actions mt-3">
                    <a href="/login" class="btn btn-primary">Login</a>
                    <a href="/register" class="btn btn-outline-primary">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($weather) && $weather): ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Weather in <?php echo htmlspecialchars($weather['city']); ?></h5>
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="https://openweathermap.org/img/wn/<?php echo $weather['icon']; ?>@2x.png" alt="Weather icon">
                        <h2 class="display-4 mb-0"><?php echo $weather['temp']; ?>&deg;C</h2>
                    </div>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($weather['description']); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>