<?php
/**
 * File Upload Helper
 * Secure file upload handling
 */

require_once APP_PATH . '/config/app.php';

function upload_file($file, $directory = 'uploads', $allowed_types = null) {
    $config = require APP_PATH . '/config/app.php';
    $uploadConfig = $config['upload'];
    
    if (!isset($file['error']) || is_array($file['error'])) {
        throw new RuntimeException('Invalid parameters.');
    }
    
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }
    
    if ($file['size'] > $uploadConfig['max_size']) {
        throw new RuntimeException('Exceeded filesize limit.');
    }
    
    $allowed = $allowed_types ?? $uploadConfig['allowed_types'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowed)) {
        throw new RuntimeException('Invalid file format.');
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = sprintf('%s.%s', sha1_file($file['tmp_name']), $extension);
    
    // Ensure upload directory exists
    $uploadDir = $uploadConfig['path'] . $directory;
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new RuntimeException('Failed to create upload directory.');
        }
    }
    
    $uploadPath = $uploadDir . '/' . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    
    // Return path relative to public directory
    return '/uploads/' . $directory . '/' . $filename;
}

function delete_file($filepath) {
    $fullPath = PUBLIC_PATH . $filepath;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}


