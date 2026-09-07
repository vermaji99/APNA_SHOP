<?php
/**
 * Home Controller
 */

require_once APP_PATH . '/models/Product.php';
require_once APP_PATH . '/models/Category.php';
require_once APP_PATH . '/models/Banner.php';

class HomeController extends Controller {
    
    public function index() {

        // Load models
        $productModel = new Product();
        $categoryModel = new Category();
        $bannerModel   = new Banner();

        // Fetch featured items and distinct latest products
        $featuredProducts = $productModel->getFeatured(8);
        $featuredIds      = array_column($featuredProducts, 'id');

        $allLatest      = $productModel->getLatest(16);
        $latestProducts = array_values(array_filter($allLatest, function($p) use ($featuredIds) {
            return !in_array($p['id'], $featuredIds);
        }));
        $latestProducts = array_slice($latestProducts, 0, 8);
        $categories     = $categoryModel->getAllActive();

        // Fetch hero banners (MAX 3)
        // Works with images stored in: /uploads/banners/<image>
        $banners = $bannerModel->getActive('hero');
        $banners = array_slice($banners, 0, 3); // limit to 3 banners

        // Render view
        $this->view('home', [
            'title'            => 'APNA-MENS - Premium Menswear',
            'featuredProducts' => $featuredProducts,
            'latestProducts'   => $latestProducts,
            'categories'       => $categories,
            'banners'          => $banners
        ]);
    }
}


