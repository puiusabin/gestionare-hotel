<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hotel Reservation System'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <?php recaptchaScript(); ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a href="/" class="navbar-brand">Hotel Reservation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a href="/contact" class="nav-link">Contact</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php $user = getCurrentUser(); ?>
                        <li class="nav-item"><a href="/rooms" class="nav-link">Rooms</a></li>
                        <?php if ($user['role'] === 'admin'): ?>
                            <li class="nav-item"><a href="/rooms/create" class="nav-link">Add Room</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><span class="nav-link text-muted small">Hello, <?php echo htmlspecialchars($user['first_name']); ?></span></li>
                        <li class="nav-item"><a href="/logout" class="btn btn-outline-danger btn-sm ms-lg-2">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a href="/login" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="/register" class="btn btn-primary btn-sm ms-lg-2">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <?php
        $flash = getFlashMessage();
        if ($flash):
        ?>
            <div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : $flash['type']; ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>