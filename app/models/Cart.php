<?php
/**
 * Cart Model
 */

class Cart extends Model {
    protected $table = 'cart';
    
    public function getCartItems($userId = null, $sessionId = null) {
        if ($userId) {
            $stmt = $this->db->prepare("
                SELECT c.*, p.name, p.price, p.sku, p.stock_quantity,
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM {$this->table} c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT c.*, p.name, p.price, p.sku, p.stock_quantity,
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM {$this->table} c
                JOIN products p ON c.product_id = p.id
                WHERE c.session_id = ?
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([$sessionId]);
        }
        return $stmt->fetchAll();
    }
    
    public function addItem($data) {
        // Check if item already exists
        $existing = $this->findExisting($data['user_id'] ?? null, $data['session_id'] ?? null, $data['product_id']);
        
        if ($existing) {
            // Update quantity
            $newQuantity = $existing['quantity'] + ($data['quantity'] ?? 1);
            return $this->update($existing['id'], ['quantity' => $newQuantity]);
        } else {
            // Create new item
            return $this->create($data);
        }
    }
    
    public function findExisting($userId, $sessionId, $productId) {
        if ($userId) {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
        } else {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE session_id = ? AND product_id = ?");
            $stmt->execute([$sessionId, $productId]);
        }
        return $stmt->fetch();
    }
    
    public function getCount($userId = null, $sessionId = null) {
        if ($userId) {
            $stmt = $this->db->prepare("SELECT SUM(quantity) as total FROM {$this->table} WHERE user_id = ?");
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare("SELECT SUM(quantity) as total FROM {$this->table} WHERE session_id = ?");
            $stmt->execute([$sessionId]);
        }
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }
    
    public function getTotal($userId = null, $sessionId = null) {
        $items = $this->getCartItems($userId, $sessionId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    public function clearCart($userId = null, $sessionId = null) {
        if ($userId) {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id = ?");
            $stmt->execute([$userId]);
        } else {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE session_id = ?");
            $stmt->execute([$sessionId]);
        }
    }
}




