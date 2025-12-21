<?php

// HTTP request validation utilities


function validateReferer()
{
    if (!isset($_SERVER['HTTP_REFERER'])) {
        return true;
    }

    $referer = $_SERVER['HTTP_REFERER'];
    $host = $_SERVER['HTTP_HOST'];

    return strpos($referer, $host) !== false;
}
