<?php
/**
 * Authentication Guard Middleware
 */

function require_auth() {
    if (!Auth::check()) {
        header('Location: /pages/login.php');
        exit;
    }
}

function require_guest() {
    if (Auth::check()) {
        header('Location: /pages/home.php');
        exit;
    }
}

function require_admin() {
    require_auth();
    if (!Auth::isAdmin()) {
        http_response_code(403);
        die('Access denied. Admin privileges required.');
    }
}

function require_vendor() {
    require_auth();
    if (!Auth::isVendor()) {
        http_response_code(403);
        die('Access denied. Vendor privileges required.');
    }
}




