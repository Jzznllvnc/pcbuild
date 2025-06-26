<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/CartItem.php';
require_once BASE_PATH . 'app/models/Product.php'; // Required for CartItem model's dependency

class CartController extends BaseController
{
    protected $cartItemModel;
    protected $productModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->cartItemModel = new CartItem($pdo);
        $this->productModel = new Product($pdo);
    }

    /**
     * Displays the shopping cart page.
     */
    public function index()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in

        $data = [
            'title' => 'Your Shopping Cart',
            'isLoggedIn' => $isLoggedIn // Pass login status to the view
            // Cart items will be loaded dynamically by JavaScript
        ];

        $this->view('cart/index', $data);
    }

    /**
     * API endpoint to get cart items for the current user.
     */
    public function getCartApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartItemModel->getCartItems($userId);
        $this->jsonResponse(['success' => true, 'cart' => $cartItems]);
    }

    /**
     * API endpoint to add or update an item in the cart.
     * Expects POST data: product_id, quantity
     */
    public function addOrUpdateCartItemApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

            if ($productId === null || $productId === false || $quantity === null || $quantity === false || $quantity <= 0) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid product ID or quantity.'], 400);
                return;
            }

            $product = $this->productModel->getProductById($productId);
            if (!$product) {
                $this->jsonResponse(['success' => false, 'error' => 'Product not found.'], 404);
                return;
            }

            $currentCartItems = $this->cartItemModel->getCartItems($userId);
            $currentQuantityInCart = 0;
            foreach ($currentCartItems as $item) {
                if ($item['id'] == $productId) {
                    $currentQuantityInCart = $item['quantity'];
                    break;
                }
            }

            if (($currentQuantityInCart + $quantity) > $product['stock']) {
                $this->jsonResponse(['success' => false, 'error' => 'Not enough stock available for this quantity. Max additional: ' . ($product['stock'] - $currentQuantityInCart)], 400);
                return;
            }

            if ($this->cartItemModel->addOrUpdateItem($userId, $productId, $quantity)) {
                $this->jsonResponse(['success' => true, 'message' => 'Product added/updated in cart.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to add/update product in cart.'], 500);
            }
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
        }
    }

    /**
     * API endpoint to set the quantity of an item in the cart.
     * Expects POST data: product_id, quantity (this is the absolute new quantity)
     */
    public function setCartItemQuantityApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $newQuantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

            if ($productId === null || $productId === false || $newQuantity === null || $newQuantity === false || $newQuantity < 0) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid product ID or quantity.'], 400);
                return;
            }

            if ($newQuantity > 0) {
                $product = $this->productModel->getProductById($productId);
                if (!$product) {
                    $this->jsonResponse(['success' => false, 'error' => 'Product not found.'], 404);
                    return;
                }
                if ($newQuantity > $product['stock']) {
                    $this->jsonResponse(['success' => false, 'error' => 'Not enough stock available. Max available: ' . $product['stock']], 400);
                    return;
                }
            }
            
            if ($this->cartItemModel->setItemQuantity($userId, $productId, $newQuantity)) {
                $this->jsonResponse(['success' => true, 'message' => 'Cart item quantity updated.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to update cart item quantity.'], 500);
            }
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
        }
    }

    /**
     * API endpoint to remove an item from the cart.
     * Expects POST data: product_id
     */
    public function removeCartItemApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);

            if ($productId === null || $productId === false) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid product ID.'], 400);
                return;
            }

            if ($this->cartItemModel->removeItem($userId, $productId)) {
                $this->jsonResponse(['success' => true, 'message' => 'Product removed from cart.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to remove product from cart.'], 500);
            }
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
        }
    }

    /**
     * API endpoint to clear all items from the cart.
     */
    public function clearCartApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];

            if ($this->cartItemModel->clearCart($userId)) {
                $this->jsonResponse(['success' => true, 'message' => 'Cart cleared.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to clear cart.'], 500);
            }
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
        }
    }

    /**
     * API endpoint to sync local cart with database cart upon login/registration.
     * Expects POST data: cart (JSON string of local cart items)
     */
    public function syncCartApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in'], 401);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $localCartJson = $_POST['cart'] ?? '[]';
            $localCart = json_decode($localCartJson, true);

            if (!is_array($localCart)) {
                $this->jsonResponse(['success' => false, 'error' => 'Invalid cart data provided.'], 400);
                return;
            }

            // Fetch current server cart items to handle merges (sum quantities)
            $serverCartItems = $this->cartItemModel->getCartItems($userId);
            $serverCartMap = [];
            foreach ($serverCartItems as $item) {
                $serverCartMap[$item['id']] = $item['quantity'];
            }

            $errors = [];
            foreach ($localCart as $item) {
                $productId = filter_var($item['id'], FILTER_VALIDATE_INT);
                $quantity = filter_var($item['quantity'], FILTER_VALIDATE_INT);

                if ($productId === false || $quantity === false || $quantity <= 0) {
                    $errors[] = "Invalid item data for product ID: " . ($item['id'] ?? 'unknown');
                    continue;
                }

                $product = $this->productModel->getProductById($productId);
                if (!$product) {
                    $errors[] = "Product with ID {$productId} not found during sync.";
                    continue;
                }

                $newTotalQuantity = ($serverCartMap[$productId] ?? 0) + $quantity;

                if ($newTotalQuantity > $product['stock']) {
                    $errors[] = "Not enough stock for product '{$product['name']}'. Requested total: {$newTotalQuantity}, available: {$product['stock']}.";
                    // Add only up to available stock if merge exceeds it
                    $quantityToAdd = $product['stock'] - ($serverCartMap[$productId] ?? 0);
                    if ($quantityToAdd > 0) {
                         $this->cartItemModel->addOrUpdateItem($userId, $productId, $quantityToAdd);
                    }
                } else {
                    $this->cartItemModel->addOrUpdateItem($userId, $productId, $quantity);
                }
            }

            if (empty($errors)) {
                $this->jsonResponse(['success' => true, 'message' => 'Cart synchronized successfully.']);
            } else {
                // Return success but with warnings about items that could not be fully synced
                $this->jsonResponse(['success' => true, 'message' => 'Cart synchronized with some issues.', 'warnings' => $errors]);
            }

        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
        }
    }
}