<?php

class Router
{
    protected $routes = [];

    /**
     * Add a route.
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $path URL path (e.g., '/', '/products/{id}')
     * @param array $callback [ControllerClassName::class, 'methodName']
     */
    public function addRoute($method, $path, $callback)
    {
        $this->routes[strtoupper($method)][$path] = $callback;
    }

    /**
     * Dispatch the request.
     */
    public function dispatch()
    {
        // Get the current request URI and method
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Trim leading/trailing slashes and remove the project subdirectory if present
        $basePath = '/pcbuild/public'; // This should match your .htaccess RewriteBase
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        $requestUri = trim($requestUri, '/');

        // Default to 'home' if the URI is empty (e.g., http://localhost/pcbuild/public/)
        if (empty($requestUri)) {
            $requestUri = 'home';
        }

        // Try to find an exact match first (e.g., /products, /login)
        if (isset($this->routes[$requestMethod][$requestUri])) {
            $callback = $this->routes[$requestMethod][$requestUri];
            $this->callAction($callback);
            return;
        }

        // Handle routes with parameters (e.g., /products/123)
        foreach ($this->routes[$requestMethod] as $routePath => $callback) {
            // Convert route path (e.g., 'products/{id}') to a regex pattern
            // Escape forward slashes, replace {param} with a capture group
            $pattern = preg_quote($routePath, '#'); // Escape the entire path first
            // Now, un-escape the parameter placeholders and make them capture groups
            $pattern = str_replace(preg_quote('{id}', '#'), '(\d+)', $pattern); // For {id} (digits only)
            $pattern = str_replace(preg_quote('{name}', '#'), '([a-zA-Z0-9_-]+)', $pattern); // Example for {name} (alphanumeric, hyphen, underscore)
            // Add other parameter types as needed, ensuring they are not over-escaped

            $pattern = '#^' . $pattern . '$#'; // Add start/end anchors for exact match

            // Special handling for the root path when converted to pattern
            if ($routePath === '/') {
                $pattern = '#^$#'; // Match empty string for root
            }

            if (preg_match($pattern, $requestUri, $matches)) {
                // Remove the full match (index 0)
                array_shift($matches);
                $this->callAction($callback, $matches);
                return;
            }
        }

        // If no route matches, show a 404 error
        header("HTTP/1.0 404 Not Found");
        echo '<div class="container mx-auto p-4">';
        echo '<h1>404 Not Found</h1>';
        echo '<p>The page you requested could not be found.</p>';
        echo '</div>';
    }

    /**
     * Call the controller method.
     * @param array $callback [ControllerClassName::class, 'methodName']
     * @param array $params Parameters extracted from the URL
     */
    protected function callAction($callback, $params = [])
    {
        list($controllerClass, $method) = $callback;

        // Check if the controller class file exists and include it
        $controllerFile = BASE_PATH . 'app/controllers/' . $controllerClass . '.php';
        if (!file_exists($controllerFile)) {
            die("Controller file not found: " . $controllerFile);
        }
        require_once $controllerFile;

        // Instantiate the controller
        // Pass the PDO object to the controller constructor if needed
        global $pdo; // Access the global PDO object from database.php
        $controllerInstance = new $controllerClass($pdo);

        // Check if the method exists in the controller
        if (!method_exists($controllerInstance, $method)) {
            die("Method '$method' not found in controller '$controllerClass'");
        }

        // Call the method with dynamic parameters
        call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Static helper for including views.
     * @param string $viewPath Path to the view file (e.g., 'home', 'products/list')
     * @param array $data Data to pass to the view
     */
    public static function view($viewPath, $data = [])
    {
        // Convert viewPath to file path, e.g., 'home' -> 'app/views/home.php'
        $filePath = BASE_PATH . 'app/views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($filePath)) {
            die("View file not found: " . $filePath);
        }

        // Extract data into local variables for the view
        extract($data);

        // Include the header and actual view file, then the footer
        require_once BASE_PATH . 'includes/header.php';
        require_once $filePath;
        require_once BASE_PATH . 'includes/footer.php';
    }
}
