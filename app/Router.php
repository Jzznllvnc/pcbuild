<?php

class Router
{
    protected $routes = [];

    /**
     * Add a GET route.
     * @param string
     * @param string
     */
    public function get($uri, $controllerAction)
    {
        $this->routes['GET'][$this->formatRouteUri($uri)] = $controllerAction;
    }

    /**
     * Add a POST route.
     * @param string
     * @param string
     */
    public function post($uri, $controllerAction)
    {
        $this->routes['POST'][$this->formatRouteUri($uri)] = $controllerAction;
    }

    /**
     * @param string
     * @param string
     * @param PDO
     */
    public function dispatch($requestUri, $requestMethod, $pdo)
    {
        $uri = $this->formatRequestUri($requestUri);
        $method = strtoupper($requestMethod);
        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "405 Method Not Allowed";
            return;
        }

        foreach ($this->routes[$method] as $routePattern => $controllerAction) {

            $regexPattern = preg_quote($routePattern, '#');
            $regexPattern = str_replace(preg_quote('{id}', '#'), '(\d+)', $regexPattern);
            $regexPattern = str_replace(preg_quote('{search_term}', '#'), '([a-zA-Z0-9_.\-]+)', $regexPattern);
            $regexPattern = preg_replace('/\\\{([a-zA-Z0-9_]+)\\\}/', '([a-zA-Z0-9_]+)', $regexPattern);
            $regexPattern = '#^' . $regexPattern . '$#';

            if (preg_match($regexPattern, $uri, $matches)) {
                array_shift($matches);

                list($controllerName, $actionName) = explode('@', $controllerAction);

                $controllerFile = BASE_PATH . 'app/controllers/' . $controllerName . '.php';
                if (!file_exists($controllerFile)) {
                    die("Controller file not found: " . $controllerFile);
                }
                require_once $controllerFile;
                $controllerInstance = new $controllerName($pdo);

                if (!method_exists($controllerInstance, $actionName)) {
                    die("Method '{$actionName}' not found in controller '{$controllerName}'");
                }
                call_user_func_array([$controllerInstance, $actionName], $matches);
                return;
            }
        }

        http_response_code(404);
        echo '<div class="container mx-auto p-4 pt-40">';
        echo '<h1>404 Not Found</h1>';
        echo '<p>The page you requested could not be found.</p>';
        echo '</div>';
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function formatRouteUri($uri)
    {
        $uri = '/' . trim($uri, '/');
        if ($uri === '//') {
            return '/';
        }
        return $uri;
    }

    /**
     * Formats the incoming request URI for matching against defined routes.
     * @param string
     * @return string normalized URI path.
     */
    protected function formatRequestUri($requestUri)
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        if ($path === false) {
            $path = $requestUri;
        }

        $basePath = '/pcbuild';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        $uri = '/' . trim($path, '/');
        if ($uri === '//') {
            return '/';
        }
        return $uri;
    }

    public function defineRoutes()
    {
        // Home Page
        $this->get('/', 'HomeController@index');
        $this->get('/home', 'HomeController@index');

        // Products Routes
        $this->get('/products', 'ProductController@index');
        $this->get('/products/{id}', 'ProductController@show');

        // Authentication Routes
        $this->get('/login', 'AuthController@showLogin');
        $this->post('/login', 'AuthController@login');
        $this->get('/register', 'AuthController@showRegister');
        $this->post('/register', 'AuthController@register');
        $this->get('/logout', 'AuthController@logout');

        // Password Reset Routes
        $this->get('/forgot-password', 'AuthController@showForgotPassword');
        $this->post('/forgot-password', 'AuthController@processForgotPasswordRequest');
        $this->get('/reset-password', 'AuthController@showResetPassword');
        $this->post('/reset-password', 'AuthController@resetPassword');

        // Cart Routes
        $this->get('/cart', 'CartController@index');

        // Cart API Endpoints
        $this->post('/cart/add', 'CartController@addOrUpdateCartItemApi');
        $this->post('/cart/update-quantity', 'CartController@setCartItemQuantityApi');
        $this->post('/cart/remove', 'CartController@removeCartItemApi');
        $this->post('/cart/clear', 'CartController@clearCartApi');
        $this->post('/cart/sync', 'CartController@syncCartApi');
        $this->get('/cart/get', 'CartController@getCartApi');

        // AI Chat Routes
        $this->get('/ai-chat-content', 'AiChatController@getChatContent');
        $this->post('/ai-chat/api', 'AiChatController@chatApi');

        // Build Rate Routes
        $this->get('/build-rate', 'BuildController@index');
        $this->post('/build-rate/get', 'BuildController@getRating');

        // Checkout Routes
        $this->get('/checkout', 'CheckoutController@index');
        $this->post('/checkout/process', 'CheckoutController@processOrder');
        $this->get('/checkout/success', 'CheckoutController@success');

        // User API Endpoints
        $this->post('/user/clear-order-notification', 'UserController@clearNewOrderNotificationApi');

        // User Order History Route
        $this->get('/orderhistory', 'UserController@orderhistory');

        // User Profile Routes
        $this->get('/profile', 'UserController@profile');
        $this->post('/profile/update-general', 'UserController@updateGeneralInformation');
        $this->post('/profile/update-phone', 'UserController@updatePhoneNumber');

        // Admin Routes
        $this->get('/admin', 'AdminController@dashboard');
        $this->get('/admin/dashboard', 'AdminController@dashboard');

        // Admin Product Management
        $this->get('/admin/products', 'AdminController@products');
        $this->get('/admin/products/create', 'AdminController@createProductForm');
        $this->post('/admin/products/create', 'AdminController@createProduct');
        $this->get('/admin/products/edit/{id}', 'AdminController@editProductForm');
        $this->post('/admin/products/edit/{id}', 'AdminController@updateProduct');
        $this->post('/admin/products/delete/{id}', 'AdminController@deleteProduct');

        // Admin User Management
        $this->get('/admin/users', 'AdminController@manageUsers');
        $this->get('/admin/users/{search_term}', 'AdminController@manageUsers');
        $this->post('/admin/users/toggle-ban/{id}', 'AdminController@toggleUserBan');
        $this->post('/admin/users/delete/{id}', 'AdminController@deleteUserAccount');
        $this->get('/admin/users/orders/{id}', 'AdminController@viewUserOrders');
    }
}