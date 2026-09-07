<?php
/**
 * Admin Dashboard Controller
 */

namespace Admin;

class DashboardController extends \Controller {
    
    public function __construct() {
        parent::__construct();
        require_admin();
    }
    
    public function index() {
        $db = \Database::getInstance();
        
        // Get statistics
        $stats = [
            'total_orders' => 0,
            'total_revenue' => 0,
            'total_users' => 0,
            'total_products' => 0,
            'pending_orders' => 0
        ];
        
        $stmt = $db->query("SELECT COUNT(*) as total FROM orders");
        $stats['total_orders'] = $stmt->fetch()['total'];
        
        $stmt = $db->query("SELECT SUM(total) as total FROM orders WHERE payment_status = 'paid'");
        $result = $stmt->fetch();
        $stats['total_revenue'] = $result['total'] ?? 0;
        
        $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role = 'customer'");
        $stats['total_users'] = $stmt->fetch()['total'];
        
        $stmt = $db->query("SELECT COUNT(*) as total FROM products");
        $stats['total_products'] = $stmt->fetch()['total'];
        
        $stmt = $db->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = $stmt->fetch()['total'];
        
        // Recent orders
        $stmt = $db->query("
            SELECT o.*, u.name as user_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT 10
        ");
        $recentOrders = $stmt->fetchAll();
        
        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard - APNA-MENS',
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }
}
