<?php
/**
 * Order Item Model
 */

class OrderItem extends Model {
    protected $table = 'order_items';
    
    public function getByOrder($orderId) {
        $stmt = $this->db->prepare("
            SELECT oi.*, p.slug,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
            ORDER BY oi.created_at ASC
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}




