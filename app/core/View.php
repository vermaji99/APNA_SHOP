<?php
/**
 * View Class
 * Handles view rendering
 */

class View {
    
    /**
     * Render view file
     */
    public static function render($view, $data = []) {
        extract($data);

        // Check multiple possible locations for the view file
        $possiblePaths = [
            VIEW_PATH . '/' . $view . '.php',  // Direct path (for admin/vendor views)
            VIEW_PATH . '/pages/' . $view . '.php',  // Pages folder (for regular pages)
        ];
        
        $viewFile = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $viewFile = $path;
                break;
            }
        }
        
        if (!$viewFile) {
            throw new Exception("View file not found: {$view}. Checked: " . implode(', ', $possiblePaths));
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        // Include layout
        require VIEW_PATH . '/layouts/main.php';
    }
    
    /**
     * Render partial/component
     */
    public static function partial($component, $data = []) {
        extract($data);
        
        $componentFile = VIEW_PATH . '/components/' . $component . '.php';
        
        if (!file_exists($componentFile)) {
            throw new Exception("Component file not found: {$component}");
        }
        
        require $componentFile;
    }
    
    /**
     * Render JSON response
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

