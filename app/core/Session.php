<?php
/**
 * Session Management Class
 * Handles secure session operations
 */

class Session {
    
    /**
     * Start session with secure settings
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require APP_PATH . '/config/app.php';
            $sessionConfig = $config['session'];
            
            ini_set('session.cookie_httponly', $sessionConfig['httponly'] ? '1' : '0');
            ini_set('session.cookie_secure', $sessionConfig['secure'] ? '1' : '0');
            ini_set('session.cookie_samesite', $sessionConfig['same_site']);
            ini_set('session.use_strict_mode', '1');
            
            session_name($sessionConfig['name']);
            session_start();
            
            // Regenerate session ID periodically for security
            if (!isset($_SESSION['created'])) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutes
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session value
     */
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    /**
     * Destroy session
     */
    public static function destroy() {
        self::start();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
    
    /**
     * Flash message - set
     */
    public static function flash($key, $value) {
        self::set('_flash_' . $key, $value);
    }
    
    /**
     * Flash message - get and remove
     */
    public static function getFlash($key, $default = null) {
        $value = self::get('_flash_' . $key, $default);
        self::remove('_flash_' . $key);
        return $value;
    }
}




