<?php
/**
 * Wishlist Model
 */

class Wishlist extends Model {
    protected $table = 'wishlist';
    
    public function getByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT w.*, p.name, p.price, p.slug,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} w
            JOIN products p ON w.product_id = p.id
            WHERE w.user_id = ? AND p.status = 'published'
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function isInWishlist($userId, $productId) {
        $stmt = $this->db->prepare("SELECT id FROM {$this->table} WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch() !== false;
    }
    
    public function add($userId, $productId) {
        // Check if already exists
        if ($this->isInWishlist($userId, $productId)) {
            return false;
        }
        
        return $this->create([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }
    
    public function remove($userId, $productId) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id = ? AND product_id = ?");
        return $stmt->execute([$userId, $productId]);
    }
}




