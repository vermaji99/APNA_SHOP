<?php
/**
 * Banner Model
 */

class Banner extends Model {
    protected $table = 'banners';
    
    public function getActive($position = null) {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $params = [];
        
        if ($position) {
            $sql .= " AND position = ?";
            $params[] = $position;
        }
        
        $sql .= " ORDER BY sort_order ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}




