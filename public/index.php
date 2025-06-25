<?php

// Start session at the very beginning of the application
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the base path of your application
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Include necessary files
require_once BASE_PATH . 'config/database.php'; // Your database connection
require_once BASE_PATH . 'app/Router.php';      // The Router class
require_once BASE_PATH . 'app/controllers/BaseController.php'; // Base Controller

// --- Centralized Model Includes ---
require_once BASE_PATH . 'app/models/User.php';
require_once BASE_PATH . 'app/models/Product.php';
require_once BASE_PATH . 'app/models/Order.php';
// --- END Centralized Model Includes ---


// Include Controllers
require_once BASE_PATH . 'app/controllers/HomeController.php';
require_once BASE_PATH . 'app/controllers/ProductController.php';
require_once BASE_PATH . 'app/controllers/AuthController.php';
require_once BASE_PATH . 'app/controllers/CartController.php';
require_once BASE_PATH . 'app/controllers/AiChatController.php';
require_once BASE_PATH . 'app/controllers/BuildController.php';
require_once BASE_PATH . 'app/controllers/CheckoutController.php';
require_once BASE_PATH . 'app/controllers/UserController.php';
require_once BASE_PATH . 'app/controllers/AdminController.php'; // Include the new AdminController

// Create a new Router instance
$router = new Router();

// Define your routes using the new get/post methods
$router->defineRoutes(); // Call the new method to define all routes

// Dispatch the request
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Pass the PDO object to the dispatch method
$router->dispatch($requestUri, $requestMethod, $pdo);

?>