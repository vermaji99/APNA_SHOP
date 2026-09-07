<?php
/**
 * Checkout Controller
 */

require_once APP_PATH . '/models/Cart.php';
require_once APP_PATH . '/models/Order.php';
require_once APP_PATH . '/models/Address.php';

class CheckoutController extends Controller {
    
    public function index() {
        require_auth();
        
        $cartModel = new Cart();
        $userId = Auth::id();
        
        $items = $cartModel->getCartItems($userId);
        
        if (empty($items)) {
            $this->redirect('/pages/cart.php');
        }
        
        // Get user addresses
        $addressModel = new Address();
        $addresses = $addressModel->findAll(['user_id' => $userId]);
        
        $subtotal = $cartModel->getTotal($userId);
        $tax = $subtotal * 0.18;
        $shipping = $subtotal >= 2000 ? 0 : 99;
        $total = $subtotal + $tax + $shipping;
        
        $this->view('checkout', [
            'title' => 'Checkout - APNA-MENS',
            'items' => $items,
            'addresses' => $addresses,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ]);
    }
    
    public function process() {
        require_auth();
        $this->verifyCsrf();
        
        require_once APP_PATH . '/helpers/sanitize.php';
        
        $cartModel = new Cart();
        $userId = Auth::id();
        
        $items = $cartModel->getCartItems($userId);
        
        if (empty($items)) {
            $this->json(['success' => false, 'message' => 'Cart is empty'], 400);
            return;
        }
        
        $billingAddressId = (int)$this->post('billing_address_id');
        $shippingAddressId = (int)$this->post('shipping_address_id');
        $paymentMethod = sanitize_string($this->post('payment_method', 'cod'));
        
        // Validate addresses
        if (!$billingAddressId || !$shippingAddressId) {
            $this->json(['success' => false, 'message' => 'Please select a shipping address'], 400);
            return;
        }
        
        $addressModel = new Address();
        $billingAddress = $addressModel->find($billingAddressId);
        $shippingAddress = $addressModel->find($shippingAddressId);
        
        if (!$billingAddress || $billingAddress['user_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid billing address'], 400);
            return;
        }
        
        if (!$shippingAddress || $shippingAddress['user_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid shipping address'], 400);
            return;
        }
        
        $subtotal = $cartModel->getTotal($userId);
        $tax = $subtotal * 0.18;
        $shipping = $subtotal >= 2000 ? 0 : 99;
        $total = $subtotal + $tax + $shipping;
        
        try {
            // Create order
            $orderModel = new Order();
            $orderItems = [];
            
            foreach ($items as $item) {
                $orderItems[] = [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? '',
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }
            
            $orderId = $orderModel->createOrder([
                'user_id' => $userId,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => 0,
                'total' => $total,
                'billing_address_id' => $billingAddressId,
                'shipping_address_id' => $shippingAddressId,
                'items' => $orderItems
            ]);
            
            if (!$orderId) {
                $this->json(['success' => false, 'message' => 'Failed to create order'], 500);
                return;
            }
            
            // Clear cart
            $cartModel->clearCart($userId);
            
            $this->json(['success' => true, 'order_id' => $orderId, 'redirect' => "/pages/order-success.php?id={$orderId}"]);
        } catch (Exception $e) {
            error_log('Checkout error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'An error occurred while processing your order'], 500);
        }
    }
}

