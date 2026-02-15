<?php

// Room-specific validation helpers

function validateRoomNumber($roomNumber)
{
    $errors = [];
    if (empty($roomNumber)) {
        $errors[] = 'Room number is required';
    }
    return $errors;
}

function validateRoomType($roomType)
{
    $errors = [];

    if (empty($roomType)) {
        $errors[] = 'Room type is required';
    } elseif (!in_array($roomType, ALLOWED_ROOM_TYPES, true)) {
        $errors[] = 'Invalid room type';
    }

    return $errors;
}

function validateCapacity($capacity)
{
    $errors = [];

    if (empty($capacity)) {
        $errors[] = 'Capacity is required';
    } elseif (!is_numeric($capacity) || $capacity < 1) {
        $errors[] = 'Capacity must be at least 1';
    }

    return $errors;
}

function validatePrice($price)
{
    $errors = [];

    if (empty($price)) {
        $errors[] = 'Price per night is required';
    } elseif (!is_numeric($price) || $price < 0) {
        $errors[] = 'Price must be a positive number';
    }

    return $errors;
}

function validateRoomData($roomNumber, $roomType, $capacity, $pricePerNight)
{
    $errors = [];

    $errors = array_merge($errors, validateRoomNumber($roomNumber));
    $errors = array_merge($errors, validateRoomType($roomType));
    $errors = array_merge($errors, validateCapacity($capacity));
    $errors = array_merge($errors, validatePrice($pricePerNight));

    return $errors;
}
