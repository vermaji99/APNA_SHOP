<?php
/**
 * Router for PHP Built-in Server
 * Routes all requests through index.php
 */

// If the requested file exists and is not a PHP file in pages/, serve it directly
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestedFile = __DIR__ . $requestUri;

// Serve static files directly if they exist
if ($requestUri !== '/' && file_exists($requestedFile) && !is_dir($requestedFile)) {
    // Don't route PHP files in pages/ directory - let index.php handle them
    if (strpos($requestUri, '/pages/') === false) {
        return false; // Serve the file
    }
}

// Route everything else through index.php
require __DIR__ . '/index.php';
