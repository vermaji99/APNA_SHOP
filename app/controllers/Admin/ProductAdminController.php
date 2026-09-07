<?php
/**
 * Admin Product Management Controller
 */

namespace Admin;

require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/helpers/sanitize.php';
require_once APP_PATH . '/helpers/validator.php';
require_once APP_PATH . '/helpers/upload.php';

class ProductAdminController extends \Controller {
    
    public function __construct() {
        parent::__construct();
        require_admin();
    }
    
    public function index() {
        $db = \Database::getInstance();
        
        // Get search and filter params
        $search = $this->get('search', '');
        $status = $this->get('status', '');
        $category = $this->get('category', '');
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Build query
        $sql = "SELECT p.*, c.name as category_name,
                (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        
        if ($category) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM products p WHERE 1=1";
        $countParams = [];
        if ($search) {
            $countSql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.description LIKE ?)";
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
            $countParams[] = $searchTerm;
        }
        if ($status) {
            $countSql .= " AND p.status = ?";
            $countParams[] = $status;
        }
        if ($category) {
            $countSql .= " AND p.category_id = ?";
            $countParams[] = $category;
        }
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($countParams);
        $total = $countStmt->fetch()['total'];
        
        // Get categories for filter
        $categoryModel = new \Category();
        $categories = $categoryModel->getAllActive();
        
        $this->view('admin/products/index', [
            'title' => 'Manage Products - Admin',
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'status' => $status,
            'category' => $category,
            'page' => $page,
            'total' => $total,
            'perPage' => $perPage
        ]);
    }
    
    public function create() {
        $categoryModel = new \Category();
        $categories = $categoryModel->getAllActive();
        
        $this->view('admin/products/create', [
            'title' => 'Add New Product - Admin',
            'categories' => $categories
        ]);
    }
    
    public function store() {
        $this->verifyCsrf();
        
        $name = \sanitize_string($this->post('name'));
        $slug = \sanitize_string($this->post('slug', ''));
        $description = \sanitize_string($this->post('description', ''));
        $shortDescription = \sanitize_string($this->post('short_description', ''));
        $sku = \sanitize_string($this->post('sku'));
        $price = (float)$this->post('price');
        $comparePrice = $this->post('compare_price') ? (float)$this->post('compare_price') : null;
        $categoryId = (int)$this->post('category_id');
        $stockQuantity = (int)$this->post('stock_quantity', 0);
        $stockStatus = \sanitize_string($this->post('stock_status', 'in_stock'));
        $status = \sanitize_string($this->post('status', 'draft'));
        $featured = ($this->post('featured') === '1' || $this->post('featured') === 1 || $this->post('featured') === 'on') ? 1 : 0;
        $weight = $this->post('weight') ? (float)$this->post('weight') : null;
        $dimensions = \sanitize_string($this->post('dimensions', ''));
        $metaTitle = \sanitize_string($this->post('meta_title', ''));
        $metaDescription = \sanitize_string($this->post('meta_description', ''));
        
        // Validation
        if (!\validate_required($name)) {
            $this->json(['success' => false, 'message' => 'Product name is required'], 400);
            return;
        }
        
        if (!\validate_required($sku)) {
            $this->json(['success' => false, 'message' => 'SKU is required'], 400);
            return;
        }
        
        if ($price <= 0) {
            $this->json(['success' => false, 'message' => 'Price must be greater than 0'], 400);
            return;
        }
        
        if (!$categoryId) {
            $this->json(['success' => false, 'message' => 'Category is required'], 400);
            return;
        }
        
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        // Check if slug exists
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        // Check if SKU exists
        $stmt = $db->prepare("SELECT id FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        if ($stmt->fetch()) {
            $this->json(['success' => false, 'message' => 'SKU already exists'], 400);
            return;
        }
        
        try {
            // Create product
            $productModel = new \Product();
            $productId = $productModel->create([
                'vendor_id' => null, // Admin created products don't have vendor
                'category_id' => $categoryId,
                'name' => $name,
                'slug' => $slug,
                'description' => $description ?: null,
                'short_description' => $shortDescription ?: null,
                'sku' => $sku,
                'price' => $price,
                'compare_price' => $comparePrice,
                'stock_quantity' => $stockQuantity,
                'stock_status' => $stockStatus,
                'status' => $status,
                'featured' => $featured,
                'weight' => $weight,
                'dimensions' => $dimensions ?: null,
                'meta_title' => $metaTitle ?: null,
                'meta_description' => $metaDescription ?: null
            ]);
            
            if (!$productId) {
                throw new \Exception('Failed to create product in database');
            }
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $imagePath = \upload_file($_FILES['image'], 'products');
                    $stmt = $db->prepare("INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES (?, ?, 1, 0)");
                    $stmt->execute([$productId, $imagePath]);
                } catch (\Exception $e) {
                    error_log('Image upload error: ' . $e->getMessage());
                    // Continue even if image upload fails
                }
            }
            
            $this->json(['success' => true, 'message' => 'Product created successfully', 'redirect' => '/admin/products']);
        } catch (\Exception $e) {
            error_log('Product creation error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->json(['success' => false, 'message' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }
    
    public function edit() {
        $id = (int)$this->get('id');
        
        $productModel = new \Product();
        $product = $productModel->find($id);
        
        if (!$product) {
            http_response_code(404);
            $this->view('404', ['title' => 'Product Not Found']);
            exit;
        }
        
        // Get product images
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC");
        $stmt->execute([$id]);
        $productImages = $stmt->fetchAll();
        
        // Get categories
        $categoryModel = new \Category();
        $categories = $categoryModel->getAllActive();
        
        $this->view('admin/products/edit', [
            'title' => 'Edit Product - Admin',
            'product' => $product,
            'productImages' => $productImages,
            'categories' => $categories
        ]);
    }
    
    public function update() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        
        $productModel = new \Product();
        $product = $productModel->find($id);
        
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Product not found'], 404);
            return;
        }
        
        $name = \sanitize_string($this->post('name'));
        $slug = \sanitize_string($this->post('slug', ''));
        $description = \sanitize_string($this->post('description', ''));
        $shortDescription = \sanitize_string($this->post('short_description', ''));
        $sku = \sanitize_string($this->post('sku'));
        $price = (float)$this->post('price');
        $comparePrice = $this->post('compare_price') ? (float)$this->post('compare_price') : null;
        $categoryId = (int)$this->post('category_id');
        $stockQuantity = (int)$this->post('stock_quantity', 0);
        $stockStatus = \sanitize_string($this->post('stock_status', 'in_stock'));
        $status = \sanitize_string($this->post('status', 'draft'));
        $featured = ($this->post('featured') === '1' || $this->post('featured') === 1 || $this->post('featured') === 'on') ? 1 : 0;
        $weight = $this->post('weight') ? (float)$this->post('weight') : null;
        $dimensions = \sanitize_string($this->post('dimensions', ''));
        $metaTitle = \sanitize_string($this->post('meta_title', ''));
        $metaDescription = \sanitize_string($this->post('meta_description', ''));
        
        // Validation
        if (!\validate_required($name)) {
            $this->json(['success' => false, 'message' => 'Product name is required'], 400);
            return;
        }
        
        if (!\validate_required($sku)) {
            $this->json(['success' => false, 'message' => 'SKU is required'], 400);
            return;
        }
        
        if ($price <= 0) {
            $this->json(['success' => false, 'message' => 'Price must be greater than 0'], 400);
            return;
        }
        
        // Check if SKU exists for another product
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM products WHERE sku = ? AND id != ?");
        $stmt->execute([$sku, $id]);
        if ($stmt->fetch()) {
            $this->json(['success' => false, 'message' => 'SKU already exists'], 400);
            return;
        }
        
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        // Check if slug exists for another product
        $stmt = $db->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        try {
            // Update product
            $updateResult = $productModel->update($id, [
                'category_id' => $categoryId,
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'short_description' => $shortDescription,
                'sku' => $sku,
                'price' => $price,
                'compare_price' => $comparePrice,
                'stock_quantity' => $stockQuantity,
                'stock_status' => $stockStatus,
                'status' => $status,
                'featured' => $featured,
                'weight' => $weight,
                'dimensions' => $dimensions,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription
            ]);
            
            if (!$updateResult) {
                throw new \Exception('Failed to update product in database');
            }
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $imagePath = \upload_file($_FILES['image'], 'products');
                    // Check if primary image exists
                    $stmt = $db->prepare("SELECT id FROM product_images WHERE product_id = ? AND is_primary = 1");
                    $stmt->execute([$id]);
                    if ($stmt->fetch()) {
                        $stmt = $db->prepare("UPDATE product_images SET image_path = ? WHERE product_id = ? AND is_primary = 1");
                        $stmt->execute([$imagePath, $id]);
                    } else {
                        $stmt = $db->prepare("INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES (?, ?, 1, 0)");
                        $stmt->execute([$id, $imagePath]);
                    }
                } catch (\Exception $e) {
                    error_log('Image upload error: ' . $e->getMessage());
                    // Continue even if image upload fails
                }
            }
            
            $this->json(['success' => true, 'message' => 'Product updated successfully', 'redirect' => '/admin/products']);
        } catch (\Exception $e) {
            error_log('Product update error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            $this->json(['success' => false, 'message' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }
    
    public function delete() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid product ID'], 400);
            return;
        }
        
        $productModel = new \Product();
        $product = $productModel->find($id);
        
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Product not found'], 404);
            return;
        }
        
        try {
            // Delete product images
            $db = \Database::getInstance();
            $stmt = $db->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
            $stmt->execute([$id]);
            $images = $stmt->fetchAll();
            
            foreach ($images as $image) {
                if (file_exists(PUBLIC_PATH . $image['image_path'])) {
                    unlink(PUBLIC_PATH . $image['image_path']);
                }
            }
            
            $stmt = $db->prepare("DELETE FROM product_images WHERE product_id = ?");
            $stmt->execute([$id]);
            
            // Delete product
            $productModel->delete($id);
            
            $this->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (Exception $e) {
            error_log('Product deletion error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to delete product'], 500);
        }
    }
}
