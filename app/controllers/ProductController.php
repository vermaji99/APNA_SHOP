<?php
/**
 * Product Controller
 */

require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';

class ProductController extends Controller {
    
    public function shop() {
        $productModel = new Product();
        $categoryModel = new Category();
        
        $categoryId = $this->get('category');
        $search = $this->get('search');
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        if ($search) {
            $products = $productModel->search($search, $perPage);
            $total = count($products);
        } elseif ($categoryId) {
            $products = $productModel->getByCategory($categoryId, $perPage, $offset);
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ? AND status = 'published'");
            $stmt->execute([$categoryId]);
            $total = $stmt->fetch()['total'];
        } else {
            $db = Database::getInstance();
            $stmt = $db->prepare("
                SELECT p.*,
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                WHERE p.status = 'published'
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$perPage, $offset]);
            $products = $stmt->fetchAll();
            
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE status = 'published'");
            $stmt->execute();
            $total = $stmt->fetch()['total'];
        }
        
        $categories = $categoryModel->getAllActive();
        
        $this->view('shop', [
            'title' => 'Shop - APNA-MENS',
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $categoryId,
            'search' => $search,
            'page' => $page,
            'total' => $total,
            'perPage' => $perPage
        ]);
    }
    
    public function show() {
        $id = (int)$this->get('id');
        $productModel = new Product();
        $product = $productModel->find($id);
        
        if (!$product || $product['status'] !== 'published') {
            http_response_code(404);
            require VIEW_PATH . '/pages/404.php';
            exit;
        }
        
        // Get product images
        $images = $productModel->getImages($id);
        
        // Get related products
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT p.*,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM products p
            WHERE p.category_id = ? AND p.id != ? AND p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT 4
        ");
        $stmt->execute([$product['category_id'], $id]);
        $relatedProducts = $stmt->fetchAll();
        
        // Increment views
        $productModel->incrementViews($id);
        
        $this->view('product', [
            'title' => $product['name'] . ' - APNA-MENS',
            'product' => $product,
            'images' => $images,
            'relatedProducts' => $relatedProducts
        ]);
    }
}

