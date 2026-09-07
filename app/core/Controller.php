<?php
/**
 * Base Controller Class
 * All controllers extend this class
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        Session::start();
    }
    
    /**
     * Render view
     */
    protected function view($view, $data = []) {
        View::render($view, $data);
    }
    
    /**
     * Render JSON response
     */
    protected function json($data, $statusCode = 200) {
        View::json($data, $statusCode);
    }
    
    /**
     * Redirect
     */
    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Get POST data (handles both form data and JSON)
     */
    protected function post($key = null, $default = null) {
        // Check if request is JSON (but not multipart/form-data)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false && strpos($contentType, 'multipart/form-data') === false) {
            $jsonData = json_decode(file_get_contents('php://input'), true);
            if ($jsonData === null) {
                $jsonData = [];
            }
            if ($key === null) {
                return $jsonData;
            }
            return $jsonData[$key] ?? $default;
        }
        
        // Regular form data (including multipart/form-data for file uploads)
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET data
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Verify CSRF token
     */
    protected function verifyCsrf() {
        // Check header first (for AJAX requests)
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        // If not in header, check POST/FormData/JSON data
        if (empty($token)) {
            $token = $this->post('csrf_token') ?? '';
        }
        
        $sessionToken = Session::get('csrf_token');
        
        if (empty($token) || $token !== $sessionToken) {
            $this->json(['success' => false, 'error' => 'Invalid CSRF token'], 403);
        }
    }
}

