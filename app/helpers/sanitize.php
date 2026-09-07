<?php
/**
 * Sanitization Helper Functions
 * XSS protection and data cleaning
 */

function sanitize_string($string) {
    return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
}

function sanitize_email($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

function sanitize_int($int) {
    return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
}

function sanitize_float($float) {
    return filter_var($float, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

function sanitize_url($url) {
    return filter_var(trim($url), FILTER_SANITIZE_URL);
}

function sanitize_array($array) {
    if (!is_array($array)) {
        return [];
    }
    
    return array_map('sanitize_string', $array);
}

function sanitize_input($data) {
    if (is_array($data)) {
        return sanitize_array($data);
    }
    return sanitize_string($data);
}




