<?php
/**
 * Category Model
 */

class Category extends Model {
    protected $table = 'categories';
    
    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ? AND status = 'active'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    public function getAllActive() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE status = 'active' 
            ORDER BY sort_order ASC, name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}




