<?php
/**
 * Order Model
 */

require_once APP_PATH . '/models/OrderItem.php';

class Order extends Model {
    protected $table = 'orders';
    
    public function createOrder($data) {
        // Generate order number
        $data['order_number'] = $this->generateOrderNumber();
        
        // Extract items before creating order (items is not a column in orders table)
        $items = $data['items'] ?? [];
        unset($data['items']);
        
        $orderId = $this->create($data);
        
        // Create order items
        if (!empty($items) && is_array($items)) {
            $orderItemModel = new OrderItem();
            foreach ($items as $item) {
                $orderItemModel->create([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku' => $item['product_sku'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }
        }
        
        return $orderId;
    }
    
    private function generateOrderNumber() {
        return 'APNA-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
    
    public function getByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getWithItems($orderId) {
        $order = $this->find($orderId);
        if (!$order) return null;
        
        $orderItemModel = new OrderItem();
        $order['items'] = $orderItemModel->getByOrder($orderId);
        
        return $order;
    }
}

