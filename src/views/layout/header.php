<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hotel Reservation System'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <?php recaptchaScript(); ?>
</head>

<body>
    <nav>
        <div class="nav-container">
            <a href="/" class="logo">Hotel Reservation</a>
            <ul class="nav-menu">
                <?php if (isLoggedIn()): ?>
                    <?php $user = getCurrentUser(); ?>
                    <li><a href="/rooms">Rooms</a></li>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li><a href="/rooms/create">Add Room</a></li>
                    <?php endif; ?>
                    <li><span>Hello, <?php echo htmlspecialchars($user['first_name']); ?></span></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="container">
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
            <div class="alert alert-<?php echo $flash['type']; ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>