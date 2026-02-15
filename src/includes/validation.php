<?php

// Input validation utilities

function validateExpectedFields(array $expectedFields)
{
    $postFields = array_keys($_POST);
    $unexpected = array_diff($postFields, $expectedFields);

    return empty($unexpected);
}

// Sanitize email address to prevent header injection
function sanitizeEmailAddress($email)
{
    $email = trim($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    // Remove any newlines or control characters
    return preg_replace('/[\r\n\t\0]/', '', $email);
}

// Sanitize name to prevent header injection
function sanitizeEmailName($name)
{
    // Strip newlines and control characters that could inject headers
    return preg_replace('/[\r\n\t\0]/', '', trim($name));
}

// Validate and sanitize ID parameter
function validateId($id, $fieldName = 'ID')
{
    if (!is_numeric($id) || $id < 1 || $id != (int)$id) {
        return false;
    }
    return (int)$id;
}

// Validate string length
function validateStringLength($str, $fieldName, $minLength = 1, $maxLength = 255)
{
    $str = trim($str);
    $length = strlen($str);
    if ($length < $minLength || $length > $maxLength) {
        return false;
    }
    return $str;
}
