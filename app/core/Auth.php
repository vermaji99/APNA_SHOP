<?php
/**
 * Authentication Class
 * Handles user authentication and authorization
 */

class Auth {
    
    /**
     * Check if user is logged in
     */
    public static function check() {
        return Session::has('user_id');
    }
    
    /**
     * Get current user
     */
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        $userId = Session::get('user_id');
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND status = 'active'");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    /**
     * Get user ID
     */
    public static function id() {
        return Session::get('user_id');
    }
    
    /**
     * Login user
     */
    public static function login($userId) {
        Session::set('user_id', $userId);
        session_regenerate_id(true);
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        Session::remove('user_id');
        Session::remove('user_role');
        Session::destroy();
    }
    
    /**
     * Check if user has role
     */
    public static function hasRole($role) {
        $user = self::user();
        return $user && $user['role'] === $role;
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return self::hasRole('admin');
    }
    
    /**
     * Check if user is vendor
     */
    public static function isVendor() {
        return self::hasRole('vendor');
    }
    
    /**
     * Require authentication
     */
    public static function requireAuth() {
        if (!self::check()) {
            header('Location: /pages/login.php');
            exit;
        }
    }
    
    /**
     * Require role
     */
    public static function requireRole($role) {
        self::requireAuth();
        if (!self::hasRole($role)) {
            http_response_code(403);
            die('Access denied');
        }
    }
}




