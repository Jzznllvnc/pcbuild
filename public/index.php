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

// --- Centralized Model Includes (NEW) ---
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

// Define your routes

// Home Page
$router->addRoute('GET', 'home', [HomeController::class, 'index']);
$router->addRoute('GET', '', [HomeController::class, 'index']); // Also handle base URL as home

// Products Routes
$router->addRoute('GET', 'products', [ProductController::class, 'index']); // List all products
$router->addRoute('GET', 'products/{id}', [ProductController::class, 'show']); // Show a single product by ID

// Authentication Routes
$router->addRoute('GET', 'login', [AuthController::class, 'showLogin']);      // Display login form
$router->addRoute('POST', 'login', [AuthController::class, 'login']);         // Process login form submission
$router->addRoute('GET', 'register', [AuthController::class, 'showRegister']); // Display registration form
$router->addRoute('POST', 'register', [AuthController::class, 'register']);   // Process registration form submission
$router->addRoute('GET', 'logout', [AuthController::class, 'logout']);       // Handle logout

// Cart Routes
$router->addRoute('GET', 'cart', [CartController::class, 'index']); // Display the shopping cart

// AI Chat Routes
$router->addRoute('GET', 'ai-chat-content', [AiChatController::class, 'getChatContent']); // Route to fetch ONLY chat content for the pop-up
$router->addRoute('POST', 'ai-chat/api', [AiChatController::class, 'chatApi']);  // API endpoint for AI chat requests (remains)

// Build Rate Routes
$router->addRoute('GET', 'build-rate', [BuildController::class, 'index']); // Display the build rating interface
$router->addRoute('POST', 'build-rate/get', [BuildController::class, 'getRating']); // API endpoint to get build rating

// Checkout Routes
$router->addRoute('GET', 'checkout', [CheckoutController::class, 'index']); // Display checkout form
$router->addRoute('POST', 'checkout/process', [CheckoutController::class, 'processOrder']); // Process order/simulated payment
$router->addRoute('GET', 'checkout/success', [CheckoutController::class, 'success']); // Order confirmation page

// User Dashboard Route
$router->addRoute('GET', 'dashboard', [UserController::class, 'dashboard']); // User dashboard and order history

// Admin Routes
$router->addRoute('GET', 'admin', [AdminController::class, 'dashboard']); // Admin dashboard
$router->addRoute('GET', 'admin/products', [AdminController::class, 'listProducts']); // List products for admin
$router->addRoute('GET', 'admin/products/create', [AdminController::class, 'createProductForm']); // Show create product form
$router->addRoute('POST', 'admin/products/create', [AdminController::class, 'createProduct']); // Handle create product submission
$router->addRoute('GET', 'admin/products/edit/{id}', [AdminController::class, 'editProductForm']); // Show edit product form
$router->addRoute('POST', 'admin/products/edit/{id}', [AdminController::class, 'updateProduct']); // Handle update product submission
$router->addRoute('POST', 'admin/products/delete/{id}', [AdminController::class, 'deleteProduct']); // Handle delete product submission

// Dispatch the request
$router->dispatch();

?>
