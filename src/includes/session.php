<?php

// Session and authentication helper functions

function configureSecureSession()
{
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_secure', '0');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_samesite', 'Strict');

    ini_set('session.gc_maxlifetime', '1800');
    ini_set('session.cookie_lifetime', '0');
}

function startSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        configureSecureSession();
        session_start();

        // Check for session timeout (30 minutes of inactivity)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            destroySession();
            return;
        }

        // Update last activity timestamp
        $_SESSION['last_activity'] = time();
    }
}

function destroySession()
{
    $_SESSION = array();

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
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
    destroySession();
    header('Location: /login');
    exit;
}

// Regenerate session ID on privilege escalation (e.g., login)
function regenerateSessionOnLogin()
{
    session_regenerate_id(true);
}
