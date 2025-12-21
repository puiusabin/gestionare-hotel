<?php

// Session and authentication helper functions

// Start session if not already started
function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Check if user is logged in
function isLoggedIn()
{
    startSession();
    return isset($_SESSION['user_id']);
}

// Require user to be logged in, redirect to login if not
function requireAuth()
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }
}

// Require user to be admin, redirect to home if not
function requireAdmin()
{
    requireAuth();
    if ($_SESSION['user_role'] !== 'admin') {
        header('Location: /');
        exit;
    }
}

// Get current logged in user data, returns null if not logged in
function getCurrentUser()
{
    startSession();
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'],
        'first_name' => $_SESSION['user_first_name'],
        'last_name' => $_SESSION['user_last_name'],
        'role' => $_SESSION['user_role']
    ];
}

// Store flash message in session for next page load
function setFlashMessage($type, $message)
{
    startSession();
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

// Get and clear flash message from session
function getFlashMessage()
{
    startSession();
    if (isset($_SESSION['flash_message'])) {
        $flash = [
            'type' => $_SESSION['flash_type'],
            'message' => $_SESSION['flash_message']
        ];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

// Destroy session and redirect to login
function logout()
{
    startSession();
    session_destroy();
    header('Location: /login');
    exit;
}
