<?php

// Google reCAPTCHA integration

$recaptchaConfig = require __DIR__ . '/../config/recaptcha.php';

define('RECAPTCHA_SITE_KEY', $recaptchaConfig['site_key']);
define('RECAPTCHA_SECRET_KEY', $recaptchaConfig['secret_key']);

function isRecaptchaEnabled()
{
    return !empty(RECAPTCHA_SITE_KEY) && !empty(RECAPTCHA_SECRET_KEY);
}

function validateRecaptcha()
{
    if (!isRecaptchaEnabled()) {
        return true; // Skip validation if not configured
    }

    if (!isset($_POST['g-recaptcha-response'])) {
        return false;
    }

    $recaptchaResponse = $_POST['g-recaptcha-response'];

    if (empty($recaptchaResponse)) {
        return false;
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === false) {
        return true; // Allow if API is unreachable
    }

    $resultJson = json_decode($result, true);

    return isset($resultJson['success']) && $resultJson['success'] === true;
}

function recaptchaField()
{
    if (!isRecaptchaEnabled()) {
        return;
    }

    echo '<div class="g-recaptcha" data-sitekey="' . htmlspecialchars(RECAPTCHA_SITE_KEY) . '"></div>';
}

function recaptchaScript()
{
    if (!isRecaptchaEnabled()) {
        return;
    }

    echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
}
