<?php
/**
 * Wishlist Controller
 */

require_once APP_PATH . '/models/Wishlist.php';

class WishlistController extends Controller {
    
    public function add() {
        require_auth();
        $this->verifyCsrf();
        
        $productId = (int)$this->post('product_id');
        $userId = Auth::id();
        
        $wishlistModel = new Wishlist();
        $result = $wishlistModel->add($userId, $productId);
        
        if ($result) {
            $this->json(['success' => true, 'message' => 'Added to wishlist']);
        } else {
            $this->json(['success' => false, 'message' => 'Already in wishlist'], 400);
        }
    }
    
    public function remove() {
        require_auth();
        $this->verifyCsrf();
        
        $productId = (int)$this->post('product_id');
        $userId = Auth::id();
        
        $wishlistModel = new Wishlist();
        $wishlistModel->remove($userId, $productId);
        
        $this->json(['success' => true, 'message' => 'Removed from wishlist']);
    }
}




