<?php
/**
 * Search Controller
 */

require_once APP_PATH . '/models/Product.php';

class SearchController extends Controller {
    
    public function index() {
        $query = $this->get('q', '');
        
        if (empty($query) || strlen($query) < 2) {
            $this->json(['success' => false, 'products' => []]);
        }
        
        $productModel = new Product();
        $products = $productModel->search($query, 10);
        
        $this->json(['success' => true, 'products' => $products]);
    }
}




