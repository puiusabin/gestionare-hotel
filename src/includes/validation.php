<?php

// Input validation utilities

function validateExpectedFields(array $expectedFields)
{
    $postFields = array_keys($_POST);
    $unexpected = array_diff($postFields, $expectedFields);

    return empty($unexpected);
}
