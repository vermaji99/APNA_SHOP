<?php
/**
 * Admin User Management Controller
 */

namespace Admin;

require_once APP_PATH . '/models/User.php';
require_once APP_PATH . '/helpers/sanitize.php';

class UserAdminController extends \Controller {
    
    public function __construct() {
        parent::__construct();
        require_admin();
    }
    
    public function index() {
        $db = \Database::getInstance();
        
        $search = $this->get('search', '');
        $role = $this->get('role', '');
        $status = $this->get('status', '');
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM orders WHERE user_id = u.id) as order_count,
                (SELECT SUM(total) FROM orders WHERE user_id = u.id AND payment_status = 'paid') as total_spent
                FROM users u
                WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($role) {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }
        
        if ($status) {
            $sql .= " AND u.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY u.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll();
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM users u WHERE 1=1";
        $countParams = [];
        if ($search) {
            $countSql .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
        }
        if ($role) {
            $countSql .= " AND u.role = ?";
            $countParams[] = $role;
        }
        if ($status) {
            $countSql .= " AND u.status = ?";
            $countParams[] = $status;
        }
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($countParams);
        $total = $countStmt->fetch()['total'];
        
        $this->view('admin/users/index', [
            'title' => 'Manage Users - Admin',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'page' => $page,
            'total' => $total,
            'perPage' => $perPage
        ]);
    }
    
    public function edit() {
        $id = (int)$this->get('id');
        
        $userModel = new \User();
        $user = $userModel->find($id);
        
        if (!$user) {
            http_response_code(404);
            $this->view('404', ['title' => 'User Not Found']);
            exit;
        }
        
        $this->view('admin/users/edit', [
            'title' => 'Edit User - Admin',
            'user' => $user
        ]);
    }
    
    public function update() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        $name = \sanitize_string($this->post('name'));
        $email = \sanitize_email($this->post('email'));
        $phone = \sanitize_string($this->post('phone', ''));
        $role = \sanitize_string($this->post('role', 'customer'));
        $status = \sanitize_string($this->post('status', 'active'));
        
        $userModel = new \User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found'], 404);
            return;
        }
        
        // Check if email exists for another user
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            $this->json(['success' => false, 'message' => 'Email already exists'], 400);
            return;
        }
        
        try {
            $userModel->update($id, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'status' => $status
            ]);
            
            $this->json(['success' => true, 'message' => 'User updated successfully', 'redirect' => '/admin/users']);
        } catch (Exception $e) {
            error_log('User update error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to update user'], 500);
        }
    }
    
    public function delete() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid user ID'], 400);
            return;
        }
        
        // Prevent deleting own account
        if ($id == \Auth::id()) {
            $this->json(['success' => false, 'message' => 'You cannot delete your own account'], 400);
            return;
        }
        
        $userModel = new \User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found'], 404);
            return;
        }
        
        try {
            $userModel->delete($id);
            $this->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (Exception $e) {
            error_log('User deletion error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }
}

