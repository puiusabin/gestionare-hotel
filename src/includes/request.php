<?php

// HTTP request validation utilities


function validateReferer()
{
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return true;  // Allow if no referer (legitimate direct access)
    }

    $referer = parse_url($_SERVER['HTTP_REFERER']);
    $host = $_SERVER['HTTP_HOST'];

    // Exact host match only
    return isset($referer['host']) && $referer['host'] === $host;
}
