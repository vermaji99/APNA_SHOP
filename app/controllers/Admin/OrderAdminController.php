<?php
/**
 * Admin Order Management Controller
 */

require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/helpers/sanitize.php';

class OrderAdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        require_admin();
    }
    
    public function index() {
        
        $orderModel = new Order();
        $db = Database::getInstance();
        
        $status = $this->get('status');
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE o.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM orders";
        if ($status) {
            $countSql .= " WHERE status = ?";
        }
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($status ? [$status] : []);
        $total = $countStmt->fetch()['total'];
        
        $this->view('admin/orders', [
            'title' => 'Order Management - Admin',
            'orders' => $orders,
            'status' => $status,
            'page' => $page,
            'total' => $total,
            'perPage' => $perPage
        ]);
    }
    
    public function updateStatus() {
        require_admin();
        $this->verifyCsrf();
        
        $orderId = (int)$this->post('order_id');
        $status = sanitize_string($this->post('status'));
        $trackingNumber = sanitize_string($this->post('tracking_number', ''));
        
        $orderModel = new Order();
        $updateData = ['status' => $status];
        
        if ($trackingNumber) {
            $updateData['tracking_number'] = $trackingNumber;
        }
        
        if ($status === 'delivered') {
            $updateData['payment_status'] = 'paid';
        }
        
        $orderModel->update($orderId, $updateData);
        
        $this->json(['success' => true, 'message' => 'Order status updated']);
    }
}

