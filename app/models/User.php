<?php
/**
 * User Model
 */

class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function createUser($data) {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->create($data);
    }
    
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return false;
        }
        
        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return false;
        }
        
        if (password_verify($password, $user['password'])) {
            // Reset failed login attempts on successful login
            $this->update($user['id'], [
                'failed_login_attempts' => 0,
                'locked_until' => null
            ]);
            return $user;
        }
        
        // Increment failed login attempts
        $failedAttempts = ($user['failed_login_attempts'] ?? 0) + 1;
        $updateData = ['failed_login_attempts' => $failedAttempts];
        
        if ($failedAttempts >= MAX_LOGIN_ATTEMPTS) {
            $updateData['locked_until'] = date('Y-m-d H:i:s', time() + LOCKOUT_DURATION);
        }
        
        $this->update($user['id'], $updateData);
        
        return false;
    }
    
    public function isLocked($userId) {
        $user = $this->find($userId);
        if (!$user) return false;
        
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return true;
        }
        
        return false;
    }
}




