<?php

function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn()
{
    startSession();
    return isset($_SESSION['user_id']);
}

function requireAuth()
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }
}

function requireAdmin()
{
    requireAuth();
    if ($_SESSION['user_role'] !== 'admin') {
        header('Location: /');
        exit;
    }
}

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

function setFlashMessage($type, $message)
{
    startSession();
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

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

function logout()
{
    startSession();
    session_destroy();
    header('Location: /login');
    exit;
}
