<?php
/**
 * APNA-MENS - Entry Point
 * Bootstrap and route requests
 */

// Define paths (only if not already defined)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', __DIR__);
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/views');
}

// Autoloader
spl_autoload_register(function ($class) {
    // Handle namespaced classes (Admin\OrderAdminController)
    $class = str_replace('\\', '/', $class);
    
    $paths = [
        APP_PATH . '/core/' . basename($class) . '.php',
        APP_PATH . '/controllers/' . $class . '.php',
        APP_PATH . '/models/' . basename($class) . '.php',
        APP_PATH . '/helpers/' . basename($class) . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Load configuration
require_once APP_PATH . '/config/constants.php';

// Load helpers
require_once APP_PATH . '/helpers/auth-guard.php';

// Start session
Session::start();

// Generate CSRF token if not exists
if (!Session::has('csrf_token')) {
    Session::set('csrf_token', bin2hex(random_bytes(32)));
}

// Route requests
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove leading slash and trailing slash
$requestUri = trim($requestUri, '/');

// Admin routes (check first, before API and pages)
if (strpos($requestUri, 'admin/') === 0) {
    $adminPath = substr($requestUri, 6);
    $adminParts = explode('/', $adminPath);
    $adminPage = pathinfo($adminParts[0], PATHINFO_FILENAME);
    
    require_admin();
    
    // Handle nested routes like admin/products/edit?id=1 or admin/products/delete/1
    $adminRoutes = [
        'dashboard' => ['Admin\\DashboardController', 'index'],
        'orders' => ['Admin\\OrderAdminController', 'index'],
        'products' => ['Admin\\ProductAdminController', 'index'],
        'products-create' => ['Admin\\ProductAdminController', 'create'],
        'products-edit' => ['Admin\\ProductAdminController', 'edit'],
        'users' => ['Admin\\UserAdminController', 'index'],
        'users-edit' => ['Admin\\UserAdminController', 'edit'],
        'categories' => ['Admin\\CategoryAdminController', 'index'],
        'categories-create' => ['Admin\\CategoryAdminController', 'create'],
        'categories-edit' => ['Admin\\CategoryAdminController', 'edit'],
    ];
    
    // Check for nested routes (e.g., products/create, products/edit, products/delete)
    if (count($adminParts) >= 2) {
        $action = $adminParts[1];
        $resource = $adminParts[0];
        
        // Handle create route
        if ($action === 'create') {
            $routeKey = $resource . '-create';
            if (isset($adminRoutes[$routeKey])) {
                list($controllerClass, $method) = $adminRoutes[$routeKey];
                $controllerInstance = new $controllerClass();
                call_user_func([$controllerInstance, $method]);
                exit;
            }
        }
        
        // Handle edit route
        if ($action === 'edit') {
            $routeKey = $resource . '-edit';
            if (isset($adminRoutes[$routeKey])) {
                list($controllerClass, $method) = $adminRoutes[$routeKey];
                $controllerInstance = new $controllerClass();
                call_user_func([$controllerInstance, $method]);
                exit;
            }
        }
        
        // Handle delete route (can be /delete or /delete/1)
        if ($action === 'delete') {
            $id = isset($adminParts[2]) ? (int)$adminParts[2] : null;
            if ($resource === 'products') {
                $controller = new \Admin\ProductAdminController();
                $_POST['id'] = $id;
                $_POST['csrf_token'] = Session::get('csrf_token');
                $controller->delete();
                exit;
            } elseif ($resource === 'users') {
                $controller = new \Admin\UserAdminController();
                $_POST['id'] = $id;
                $_POST['csrf_token'] = Session::get('csrf_token');
                $controller->delete();
                exit;
            } elseif ($resource === 'categories') {
                $controller = new \Admin\CategoryAdminController();
                $_POST['id'] = $id;
                $_POST['csrf_token'] = Session::get('csrf_token');
                $controller->delete();
                exit;
            }
        }
    }
    
    if (isset($adminRoutes[$adminPage])) {
        list($controllerClass, $method) = $adminRoutes[$adminPage];
        $controllerInstance = new $controllerClass();
        call_user_func([$controllerInstance, $method]);
        exit;
    }
}

// Vendor routes (check before API and pages)
if (strpos($requestUri, 'vendor/') === 0) {
    $vendorPath = substr($requestUri, 7);
    $vendorParts = explode('/', $vendorPath);
    $vendorPage = pathinfo($vendorParts[0], PATHINFO_FILENAME);
    
    require_vendor();
    
    if ($vendorPage === 'dashboard') {
        $db = Database::getInstance();
        $vendorId = Auth::id();
        
        // Get vendor stats
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE vendor_id = (SELECT id FROM vendors WHERE user_id = ?)");
        $stmt->execute([$vendorId]);
        $productCount = $stmt->fetch()['total'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE p.vendor_id = (SELECT id FROM vendors WHERE user_id = ?)");
        $stmt->execute([$vendorId]);
        $orderCount = $stmt->fetch()['total'];
        
        View::render('vendor/dashboard', [
            'title' => 'Vendor Dashboard - APNA-MENS',
            'productCount' => $productCount,
            'orderCount' => $orderCount
        ]);
        exit;
    }
}

// API Routes
if (strpos($requestUri, 'api/') === 0) {
    $apiPath = substr($requestUri, 4);
    $parts = explode('/', $apiPath);
    
    $controller = $parts[0] ?? 'home';
    $action = $parts[1] ?? 'index';
    
    // Special handling for search
    if ($controller === 'search') {
        $searchController = new SearchController();
        $searchController->index();
        exit;
    }
    
    // Handle admin API routes (api/admin/products/store, api/admin/users/update, etc.)
    if ($controller === 'admin') {
        require_admin();
        $subController = $parts[1] ?? 'dashboard';
        
        // Handle nested routes like admin/orders/updateStatus or admin/products/store
        if (count($parts) >= 3) {
            $action = $parts[2]; // store, update, delete, updateStatus
            // Map resource names to controller classes
            $controllerMap = [
                'products' => 'Admin\\ProductAdminController',
                'product' => 'Admin\\ProductAdminController',
                'users' => 'Admin\\UserAdminController',
                'user' => 'Admin\\UserAdminController',
                'categories' => 'Admin\\CategoryAdminController',
                'category' => 'Admin\\CategoryAdminController',
                'orders' => 'Admin\\OrderAdminController',
                'order' => 'Admin\\OrderAdminController',
            ];
            
            if (isset($controllerMap[$subController])) {
                $controllerClass = $controllerMap[$subController];
            } else {
                $controllerClass = 'Admin\\' . ucfirst($subController) . 'AdminController';
            }
        } else {
            $action = 'index';
            $controllerClass = 'Admin\\' . ucfirst($subController) . 'AdminController';
        }
    } else {
        $controllerClass = ucfirst($controller) . 'Controller';
    }
    
    // Convert kebab-case action to camelCase (e.g., create-address -> createAddress)
    $action = str_replace('-', '', ucwords($action, '-'));
    $action = lcfirst($action);
    
    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();
        if (method_exists($controllerInstance, $action)) {
            call_user_func([$controllerInstance, $action]);
            exit;
        }
    }
    
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

// This block is now handled above

// Vendor routes (before pages)
if (strpos($requestUri, 'vendor/') === 0) {
    $vendorPath = substr($requestUri, 7);
    $vendorParts = explode('/', $vendorPath);
    $vendorPage = pathinfo($vendorParts[0], PATHINFO_FILENAME);
    
    require_once APP_PATH . '/helpers/auth-guard.php';
    require_vendor();
    
    if ($vendorPage === 'dashboard') {
        $db = Database::getInstance();
        $vendorId = Auth::id();
        
        // Get vendor stats
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM products WHERE vendor_id = (SELECT id FROM vendors WHERE user_id = ?)");
        $stmt->execute([$vendorId]);
        $productCount = $stmt->fetch()['total'];
        
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE p.vendor_id = (SELECT id FROM vendors WHERE user_id = ?)");
        $stmt->execute([$vendorId]);
        $orderCount = $stmt->fetch()['total'];
        
        View::render('vendor/dashboard', [
            'title' => 'Vendor Dashboard - APNA-MENS',
            'productCount' => $productCount,
            'orderCount' => $orderCount
        ]);
        exit;
    }
}

// Page Routes
if (strpos($requestUri, 'pages/') === 0 || $requestUri === '' || $requestUri === 'pages') {
    // Handle root and pages routes
    if ($requestUri === '' || $requestUri === 'pages') {
        $pagePath = 'home';
    } else {
        $pagePath = substr($requestUri, 6); // Remove 'pages/'
    }
    
    // Remove .php extension if present
    $pagePath = preg_replace('/\.php$/', '', $pagePath);
    $pageFile = str_replace('/', '-', $pagePath);
    
    // Map page routes to controllers
    $pageRoutes = [
        'home' => ['HomeController', 'index'],
        'login' => ['AuthController', 'login'],
        'signup' => ['AuthController', 'signup'],
        'shop' => ['ProductController', 'shop'],
        'product' => ['ProductController', 'show'],
        'cart' => ['CartController', 'index'],
        'checkout' => ['CheckoutController', 'index'],
        'order-success' => ['OrderController', 'success'],
        'order-details' => ['OrderController', 'details'],
        'user-orders' => ['OrderController', 'list'],
        'user-profile' => ['UserController', 'profile'],
        'wishlist' => ['UserController', 'wishlist'],
        'about' => null, // Will load view directly
        'contact' => null, // Will load view directly
    ];
    
    $pageName = pathinfo($pageFile, PATHINFO_FILENAME);
    
    if (isset($pageRoutes[$pageName]) && $pageRoutes[$pageName] !== null) {
        list($controllerClass, $method) = $pageRoutes[$pageName];
        $controllerInstance = new $controllerClass();
        call_user_func([$controllerInstance, $method]);
        exit;
    }
    
    // Try to load page directly
    $pageFile = VIEW_PATH . '/pages/' . $pageName . '.php';
    if (file_exists($pageFile)) {
        $title = ucfirst(str_replace('-', ' ', $pageName)) . ' - APNA-MENS';
        ob_start();
        require $pageFile;
        $content = ob_get_clean();
        require VIEW_PATH . '/layouts/main.php';
        exit;
    }
}

// Default: redirect to home
if ($requestUri !== '') {
    http_response_code(404);
    require VIEW_PATH . '/pages/404.php';
} else {
    header('Location: /pages/home.php');
}
exit;

