<?php

// CSRF token generation and validation

// Generate CSRF token and store in session
function generateCsrfToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Get current CSRF token
function getCsrfToken()
{
    return $_SESSION['csrf_token'] ?? generateCsrfToken();
}

// Validate CSRF token from POST request
function validateCsrfToken()
{
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Output CSRF token as hidden form field
function csrfField()
{
    $token = getCsrfToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}
