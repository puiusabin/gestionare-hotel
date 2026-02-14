<?php



function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!empty($name)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

loadEnv(__DIR__ . '/../../.env');
