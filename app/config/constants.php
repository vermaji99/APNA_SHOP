<?php
/**
 * Application Constants
 */

// Define paths only if not already defined (in case loaded from index.php)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__))); // Go up from app/config to root
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', ROOT_PATH . '/public');
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/views');
}

// User Roles
define('ROLE_CUSTOMER', 'customer');
define('ROLE_VENDOR', 'vendor');
define('ROLE_ADMIN', 'admin');

// Order Statuses
define('ORDER_PENDING', 'pending');
define('ORDER_PROCESSING', 'processing');
define('ORDER_SHIPPED', 'shipped');
define('ORDER_DELIVERED', 'delivered');
define('ORDER_CANCELLED', 'cancelled');

// Payment Statuses
define('PAYMENT_PENDING', 'pending');
define('PAYMENT_PAID', 'paid');
define('PAYMENT_FAILED', 'failed');

// Product Statuses
define('PRODUCT_DRAFT', 'draft');
define('PRODUCT_PUBLISHED', 'published');
define('PRODUCT_ARCHIVED', 'archived');

// Rate Limiting
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 300); // 5 minutes in seconds

