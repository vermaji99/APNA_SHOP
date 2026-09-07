<?php
/**
 * Product Model
 */

class Product extends Model {
    protected $table = 'products';
    
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function getFeatured($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} p
            WHERE p.status = 'published' AND p.featured = 1
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getLatest($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} p
            WHERE p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function search($query, $limit = 20) {
        $searchTerm = "%{$query}%";
        $stmt = $this->db->prepare("
            SELECT p.*,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} p
            WHERE p.status = 'published' 
            AND (p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)
            ORDER BY p.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
        return $stmt->fetchAll();
    }
    
    public function getByCategory($categoryId, $limit = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM {$this->table} p
            WHERE p.category_id = ? AND p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$categoryId, $limit, $offset]);
        return $stmt->fetchAll();
    }
    
    public function getImages($productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM product_images 
            WHERE product_id = ? 
            ORDER BY is_primary DESC, sort_order ASC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }
    
    public function incrementViews($productId) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        $stmt->execute([$productId]);
    }
}




