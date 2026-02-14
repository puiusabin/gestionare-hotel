<?php

// Google reCAPTCHA integration

// Prioritize environment variables, fallback to config file if it exists
$siteKey = env('RECAPTCHA_SITE_KEY');
$secretKey = env('RECAPTCHA_SECRET_KEY');

if (!$siteKey || !$secretKey) {
    $configFile = __DIR__ . '/../config/recaptcha.php';
    if (file_exists($configFile)) {
        $recaptchaConfig = require $configFile;
        $siteKey = $siteKey ?: ($recaptchaConfig['site_key'] ?? '');
        $secretKey = $secretKey ?: ($recaptchaConfig['secret_key'] ?? '');
    }
}

define('RECAPTCHA_SITE_KEY', $siteKey);
define('RECAPTCHA_SECRET_KEY', $secretKey);

function isRecaptchaEnabled()
{
    if (env('APP_ENV') === 'local') {
        return false;
    }
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

    if (!isset($resultJson['success']) || $resultJson['success'] !== true) {
        return false;
    }

    if (isset($resultJson['score'])) {
        return $resultJson['score'] >= 0.5;
    }

    return true;
}

function recaptchaField()
{
    if (!isRecaptchaEnabled()) {
        return;
    }

    echo '<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">';
}

function recaptchaScript()
{
    if (!isRecaptchaEnabled()) {
        return;
    }

    echo '<script src="https://www.google.com/recaptcha/api.js?render=' . htmlspecialchars(RECAPTCHA_SITE_KEY) . '"></script>';
}

function recaptchaExecute($action = 'submit')
{
    if (!isRecaptchaEnabled()) {
        return;
    }

    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute('<?php echo htmlspecialchars(RECAPTCHA_SITE_KEY); ?>', {action: '<?php echo htmlspecialchars($action); ?>'})
                            .then(function(token) {
                                document.getElementById('g-recaptcha-response').value = token;
                                form.submit();
                            });
                    });
                });
            });
        });
    </script>
    <?php
}
