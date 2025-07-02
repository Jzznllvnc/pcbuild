<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Asia/Manila');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the base path of your application
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'app/Router.php';
require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/User.php';
require_once BASE_PATH . 'app/models/Product.php';
require_once BASE_PATH . 'app/models/Order.php';


// Include Controllers
require_once BASE_PATH . 'app/controllers/HomeController.php';
require_once BASE_PATH . 'app/controllers/ProductController.php';
require_once BASE_PATH . 'app/controllers/AuthController.php';
require_once BASE_PATH . 'app/controllers/CartController.php';
require_once BASE_PATH . 'app/controllers/AiChatController.php';
require_once BASE_PATH . 'app/controllers/BuildController.php';
require_once BASE_PATH . 'app/controllers/CheckoutController.php';
require_once BASE_PATH . 'app/controllers/UserController.php';
require_once BASE_PATH . 'app/controllers/AdminController.php';

$router = new Router();
$router->defineRoutes();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$router->dispatch($requestUri, $requestMethod, $pdo);

?>