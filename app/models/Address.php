<?php
/**
 * Address Model
 */

class Address extends Model {
    protected $table = 'addresses';
    
    public function getDefault($userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = ? AND is_default = 1 LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
}




