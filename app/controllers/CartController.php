<?php
/**
 * Cart Controller
 */

require_once APP_PATH . '/models/Cart.php';
require_once APP_PATH . '/models/Product.php';

class CartController extends Controller {
    
    public function index() {
        require_auth();
        
        $cartModel = new Cart();
        $userId = Auth::id();
        $sessionId = session_id();
        
        $items = $cartModel->getCartItems($userId, $sessionId);
        $subtotal = $cartModel->getTotal($userId, $sessionId);
        
        // Calculate tax and shipping
        $tax = $subtotal * 0.18; // 18% GST
        $shipping = $subtotal >= 2000 ? 0 : 99;
        $total = $subtotal + $tax + $shipping;
        
        $this->view('cart', [
            'title' => 'Shopping Cart - APNA-MENS',
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }
    
    public function add() {
        $this->verifyCsrf();
        
        $productId = (int)$this->post('product_id');
        $quantity = max(1, (int)$this->post('quantity', 1));
        
        $productModel = new Product();
        $product = $productModel->find($productId);
        
        if (!$product || $product['status'] !== 'published') {
            $this->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        
        if ($product['stock_quantity'] < $quantity) {
            $this->json(['success' => false, 'message' => 'Insufficient stock'], 400);
        }
        
        $cartModel = new Cart();
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = session_id();
        
        $cartModel->addItem([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
        
        $count = $cartModel->getCount($userId, $sessionId);
        
        $this->json(['success' => true, 'message' => 'Added to cart', 'count' => $count]);
    }
    
    public function update() {
        $this->verifyCsrf();
        
        $cartId = (int)$this->post('cart_id');
        $quantity = max(1, (int)$this->post('quantity', 1));
        
        $cartModel = new Cart();
        $cartItem = $cartModel->find($cartId);
        
        if (!$cartItem) {
            $this->json(['success' => false, 'message' => 'Cart item not found'], 404);
        }
        
        $cartModel->update($cartId, ['quantity' => $quantity]);
        
        $this->json(['success' => true, 'message' => 'Cart updated']);
    }
    
    public function remove() {
        $this->verifyCsrf();
        
        $cartId = (int)$this->post('cart_id');
        
        $cartModel = new Cart();
        $cartItem = $cartModel->find($cartId);
        
        if (!$cartItem) {
            $this->json(['success' => false, 'message' => 'Cart item not found'], 404);
        }
        
        $cartModel->delete($cartId);
        
        $this->json(['success' => true, 'message' => 'Item removed']);
    }
    
    public function count() {
        $cartModel = new Cart();
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = session_id();
        
        $count = $cartModel->getCount($userId, $sessionId);
        
        $this->json(['success' => true, 'count' => $count]);
    }
}




