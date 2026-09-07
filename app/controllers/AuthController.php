<?php
/**
 * Authentication Controller
 */

require_once APP_PATH . '/helpers/sanitize.php';
require_once APP_PATH . '/helpers/validator.php';
require_once APP_PATH . '/models/User.php';

class AuthController extends Controller {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->view('login', [
                'title' => 'Login - APNA-MENS'
            ]);
        }
    }
    
    private function handleLogin() {
        $this->verifyCsrf();
        
        $email = sanitize_email($this->post('email'));
        $password = $this->post('password');
        
        if (!validate_email($email) || !validate_required($password)) {
            $this->json(['success' => false, 'message' => 'Invalid email or password'], 400);
            return;
        }
        
        $userModel = new User();
        $user = $userModel->verifyPassword($email, $password);
        
        if ($user) {
            Auth::login($user['id']);
            Session::set('user_role', $user['role']);
            
            $redirect = '/pages/home.php';
            if ($user['role'] === 'admin') {
                $redirect = '/admin/dashboard.php';
            } elseif ($user['role'] === 'vendor') {
                $redirect = '/vendor/dashboard.php';
            }
            
            $this->json(['success' => true, 'message' => 'Login successful', 'redirect' => $redirect]);
        } else {
            $this->json(['success' => false, 'message' => 'Invalid email or password'], 401);
        }
    }
    
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSignup();
        } else {
            $this->view('signup', [
                'title' => 'Sign Up - APNA-MENS'
            ]);
        }
    }
    
    private function handleSignup() {
        $this->verifyCsrf();
        
        $name = sanitize_string($this->post('name'));
        $email = sanitize_email($this->post('email'));
        $password = $this->post('password');
        $phone = sanitize_string($this->post('phone'));
        
        // Validation
        if (!validate_required($name) || !validate_length($name, 2, 100)) {
            $this->json(['success' => false, 'message' => 'Name must be between 2 and 100 characters'], 400);
            return;
        }
        
        if (!validate_email($email)) {
            $this->json(['success' => false, 'message' => 'Invalid email address'], 400);
            return;
        }
        
        if (!validate_password($password)) {
            $this->json(['success' => false, 'message' => 'Password must be at least 8 characters'], 400);
            return;
        }
        
        $userModel = new User();
        
        // Check if email exists
        if ($userModel->findByEmail($email)) {
            $this->json(['success' => false, 'message' => 'Email already registered'], 400);
            return;
        }
        
        // Create user
        $userId = $userModel->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => 'customer',
            'status' => 'active'
        ]);
        
        if ($userId) {
            $this->json(['success' => true, 'message' => 'Account created successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to create account'], 500);
        }
    }
    
    public function logout() {
        Auth::logout();
        $this->json(['success' => true, 'message' => 'Logged out successfully']);
    }
}

