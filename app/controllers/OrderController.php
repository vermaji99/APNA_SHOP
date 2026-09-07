<?php
/**
 * Order Controller
 */

require_once APP_PATH . '/models/Order.php';

class OrderController extends Controller {
    
    public function success() {
        require_auth();
        
        $orderId = (int)$this->get('id');
        $userId = Auth::id();
        
        $orderModel = new Order();
        $order = $orderModel->getWithItems($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            exit;
        }
        
        $this->view('order-success', [
            'title' => 'Order Confirmed - APNA-MENS',
            'order' => $order
        ]);
    }
    
    public function list() {
        require_auth();
        
        $orderModel = new Order();
        $orders = $orderModel->getByUser(Auth::id());
        
        $this->view('user-orders', [
            'title' => 'My Orders - APNA-MENS',
            'orders' => $orders
        ]);
    }
    
    public function details() {
        require_auth();
        
        $orderId = (int)$this->get('id');
        $userId = Auth::id();
        
        $orderModel = new Order();
        $order = $orderModel->getWithItems($orderId);
        
        if (!$order || $order['user_id'] != $userId) {
            http_response_code(404);
            $this->view('404', [
                'title' => 'Order Not Found - APNA-MENS'
            ]);
            exit;
        }
        
        // Get addresses
        require_once APP_PATH . '/models/Address.php';
        $addressModel = new Address();
        $billingAddress = $order['billing_address_id'] ? $addressModel->find($order['billing_address_id']) : null;
        $shippingAddress = $order['shipping_address_id'] ? $addressModel->find($order['shipping_address_id']) : null;
        
        $this->view('order-details', [
            'title' => 'Order Details - APNA-MENS',
            'order' => $order,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress
        ]);
    }
}


