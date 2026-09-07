<?php
/**
 * Validation Helper Functions
 */

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10 && strlen($phone) <= 15;
}

function validate_password($password) {
    return strlen($password) >= 8;
}

function validate_required($value) {
    if ($value === null) {
        return false;
    }
    if (is_string($value)) {
        return !empty(trim($value));
    }
    return !empty($value);
}

function validate_length($string, $min = 0, $max = null) {
    $length = strlen(trim($string));
    if ($length < $min) {
        return false;
    }
    if ($max !== null && $length > $max) {
        return false;
    }
    return true;
}

function validate_numeric($value) {
    return is_numeric($value);
}

function validate_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}


