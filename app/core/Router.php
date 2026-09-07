<?php
/**
 * Router Class
 * Handles routing and dispatches requests to controllers
 */

class Router {
    private $routes = [];
    private $middlewares = [];
    
    /**
     * Add route
     */
    public function add($method, $path, $handler, $middleware = []) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    /**
     * GET route
     */
    public function get($path, $handler, $middleware = []) {
        $this->add('GET', $path, $handler, $middleware);
    }
    
    /**
     * POST route
     */
    public function post($path, $handler, $middleware = []) {
        $this->add('POST', $path, $handler, $middleware);
    }
    
    /**
     * Dispatch request
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        
        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                // Execute middlewares
                foreach ($route['middleware'] as $middleware) {
                    if (is_callable($middleware)) {
                        $middleware();
                    } elseif (class_exists($middleware)) {
                        (new $middleware)->handle();
                    }
                }
                
                // Execute handler
                $handler = $route['handler'];
                
                if (is_callable($handler)) {
                    return call_user_func($handler, $matches);
                } elseif (is_string($handler) && strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                    $controllerClass = $controller . 'Controller';
                    
                    if (class_exists($controllerClass)) {
                        $controllerInstance = new $controllerClass();
                        if (method_exists($controllerInstance, $method)) {
                            return call_user_func_array([$controllerInstance, $method], array_slice($matches, 1));
                        }
                    }
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        require VIEW_PATH . '/pages/404.php';
        exit;
    }
    
    /**
     * Convert route pattern to regex
     */
    private function convertToRegex($path) {
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}




