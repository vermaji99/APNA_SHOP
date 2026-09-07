<?php
/**
 * Admin Category Management Controller
 */

namespace Admin;

require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/helpers/sanitize.php';

class CategoryAdminController extends \Controller {
    
    public function __construct() {
        parent::__construct();
        require_admin();
    }
    
    public function index() {
        $categoryModel = new \Category();
        $categories = $categoryModel->findAll([], 'sort_order ASC, name ASC');
        
        $this->view('admin/categories/index', [
            'title' => 'Manage Categories - Admin',
            'categories' => $categories
        ]);
    }
    
    public function create() {
        $categoryModel = new \Category();
        $categories = $categoryModel->findAll([], 'name ASC');
        
        $this->view('admin/categories/create', [
            'title' => 'Add New Category - Admin',
            'categories' => $categories
        ]);
    }
    
    public function store() {
        $this->verifyCsrf();
        
        $name = \sanitize_string($this->post('name'));
        $slug = \sanitize_string($this->post('slug', ''));
        $description = \sanitize_string($this->post('description', ''));
        $parentId = $this->post('parent_id') ? (int)$this->post('parent_id') : null;
        $sortOrder = (int)$this->post('sort_order', 0);
        $status = \sanitize_string($this->post('status', 'active'));
        
        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Category name is required'], 400);
            return;
        }
        
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        // Check if slug exists
        $categoryModel = new \Category();
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        try {
            $categoryId = $categoryModel->create([
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'parent_id' => $parentId,
                'sort_order' => $sortOrder,
                'status' => $status
            ]);
            
            $this->json(['success' => true, 'message' => 'Category created successfully', 'redirect' => '/admin/categories']);
        } catch (Exception $e) {
            error_log('Category creation error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to create category'], 500);
        }
    }
    
    public function edit() {
        $id = (int)$this->get('id');
        
        $categoryModel = new \Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            http_response_code(404);
            $this->view('404', ['title' => 'Category Not Found']);
            exit;
        }
        
        $categories = $categoryModel->findAll([], 'name ASC');
        
        $this->view('admin/categories/edit', [
            'title' => 'Edit Category - Admin',
            'category' => $category,
            'categories' => $categories
        ]);
    }
    
    public function update() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        $name = \sanitize_string($this->post('name'));
        $slug = \sanitize_string($this->post('slug', ''));
        $description = \sanitize_string($this->post('description', ''));
        $parentId = $this->post('parent_id') ? (int)$this->post('parent_id') : null;
        $sortOrder = (int)$this->post('sort_order', 0);
        $status = \sanitize_string($this->post('status', 'active'));
        
        $categoryModel = new \Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->json(['success' => false, 'message' => 'Category not found'], 404);
            return;
        }
        
        if (empty($name)) {
            $this->json(['success' => false, 'message' => 'Category name is required'], 400);
            return;
        }
        
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        }
        
        // Check if slug exists for another category
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT id FROM categories WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $id]);
        if ($stmt->fetch()) {
            $slug .= '-' . time();
        }
        
        // Prevent setting parent to self or child
        if ($parentId == $id) {
            $this->json(['success' => false, 'message' => 'Category cannot be its own parent'], 400);
            return;
        }
        
        try {
            $categoryModel->update($id, [
                'name' => $name,
                'slug' => $slug,
                'description' => $description,
                'parent_id' => $parentId,
                'sort_order' => $sortOrder,
                'status' => $status
            ]);
            
            $this->json(['success' => true, 'message' => 'Category updated successfully', 'redirect' => '/admin/categories']);
        } catch (Exception $e) {
            error_log('Category update error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to update category'], 500);
        }
    }
    
    public function delete() {
        $this->verifyCsrf();
        
        $id = (int)$this->post('id');
        
        if (!$id) {
            $this->json(['success' => false, 'message' => 'Invalid category ID'], 400);
            return;
        }
        
        // Check if category has products
        $db = \Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            $this->json(['success' => false, 'message' => 'Cannot delete category with products'], 400);
            return;
        }
        
        // Check if category has children
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM categories WHERE parent_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result['total'] > 0) {
            $this->json(['success' => false, 'message' => 'Cannot delete category with subcategories'], 400);
            return;
        }
        
        $categoryModel = new \Category();
        $category = $categoryModel->find($id);
        
        if (!$category) {
            $this->json(['success' => false, 'message' => 'Category not found'], 404);
            return;
        }
        
        try {
            $categoryModel->delete($id);
            $this->json(['success' => true, 'message' => 'Category deleted successfully']);
        } catch (Exception $e) {
            error_log('Category deletion error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Failed to delete category'], 500);
        }
    }
}

