<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/Product.php';
require_once BASE_PATH . 'app/models/User.php';
require_once BASE_PATH . 'app/models/Order.php';

class AdminController extends BaseController
{
    protected $productModel;
    protected $userModel;
    protected $orderModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->productModel = new Product($pdo);
        $this->userModel = new User($pdo);
        $this->orderModel = new Order($pdo);

        // Ensure only admins can access these pages
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $_SESSION['error'] = 'Access denied. You must be an administrator to access this section.';
            header('Location: /pcbuild/public/login'); // Redirect non-admins to login
            exit();
        }
    }

    /**
     * Displays the main admin dashboard.
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        $this->view('admin/dashboard', $data);
    }

    /**
     * Lists all products for admin management.
     */
    public function products() // Method renamed from listProducts to products
    {
        // Use getProducts with large limits and offset 0 to fetch all products for admin view
        $products = $this->productModel->getProducts(1000, 0); // Fetch up to 1000 products, assuming that's "all" for practical purposes

        $data = [
            'title' => 'Manage Products',
            'products' => $products,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['success']);
        unset($_SESSION['error']);

        $this->view('admin/products/index', $data);
    }

    /**
     * Displays the form to create a new product.
     */
    public function createProductForm() // Renamed for clarity (GET request)
    {
        $data = [
            'title' => 'Add New Product',
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['error']);
        $this->view('admin/products/create', $data);
    }

    /**
     * Handles the submission of the new product form.
     */
    public function createProduct() // This handles the POST request
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request.';
            header('Location: /pcbuild/public/admin/products/create');
            exit();
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'image_url' => trim($_POST['image_url'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'stock' => (int)($_POST['stock'] ?? 0)
        ];

        // Basic validation
        if (empty($data['name']) || empty($data['price']) || $data['price'] <= 0 || empty($data['category'])) {
            $_SESSION['error'] = 'Name, price (must be positive), and category are required.';
            header('Location: /pcbuild/public/admin/products/create');
            exit();
        }
        if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
             $_SESSION['error'] = 'Invalid image URL provided.';
             header('Location: /pcbuild/public/admin/products/create');
             exit();
        }


        $productId = $this->productModel->createProduct(
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image_url'],
            $data['category'],
            $data['stock']
        );


        if ($productId) {
            $_SESSION['success'] = 'Product "' . htmlspecialchars($data['name']) . '" added successfully!';
            header('Location: /pcbuild/public/admin/products');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to add product. Please try again.';
            header('Location: /pcbuild/public/admin/products/create');
            exit();
        }
    }

    /**
     * Displays the form to edit an existing product.
     * @param int $id Product ID.
     */
    public function editProductForm($id) // Renamed for clarity (GET request)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Product not found.';
            header('Location: /pcbuild/public/admin/products');
            exit();
        }

        $data = [
            'title' => 'Edit Product: ' . htmlspecialchars($product['name']),
            'product' => $product,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['error']);
        $this->view('admin/products/edit', $data);
    }

    /**
     * Handles the submission of the edit product form.
     * @param int $id Product ID.
     */
    public function updateProduct($id) // This handles the POST request
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request.';
            header('Location: /pcbuild/public/admin/products/edit/' . $id);
            exit();
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'image_url' => trim($_POST['image_url'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'stock' => (int)($_POST['stock'] ?? 0)
        ];

        // Basic validation
        if (empty($data['name']) || empty($data['price']) || $data['price'] <= 0 || empty($data['category'])) {
            $_SESSION['error'] = 'Name, price (must be positive), and category are required.';
            header('Location: /pcbuild/public/admin/products/edit/' . $id);
            exit();
        }
        if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
             $_SESSION['error'] = 'Invalid image URL provided.';
             header('Location: /pcbuild/public/admin/products/edit/' . $id);
             exit();
        }

        $updated = $this->productModel->updateProduct(
            $id,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['image_url'],
            $data['category'],
            $data['stock']
        );

        if ($updated) {
            $_SESSION['success'] = 'Product "' . htmlspecialchars($data['name']) . '" updated successfully!';
            header('Location: /pcbuild/public/admin/products');
            exit();
        } else {
            $_SESSION['error'] = 'Failed to update product. Please try again or check if changes were made.';
            header('Location: /pcbuild/public/admin/products/edit/' . $id);
            exit();
        }
    }

    /**
     * Handles product deletion.
     * @param int $id Product ID.
     */
    public function deleteProduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Expect POST for deletion for security
            $_SESSION['error'] = 'Invalid request method for deletion.';
            header('Location: /pcbuild/public/admin/products');
            exit();
        }

        $deleted = $this->productModel->deleteProduct($id);

        if ($deleted === 'referenced') {
            $_SESSION['error'] = 'Cannot delete product: It is linked to existing orders.';
        } elseif ($deleted) {
            $_SESSION['success'] = 'Product deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete product. It might not exist.';
        }
        header('Location: /pcbuild/public/admin/products');
        exit();
    }


    // User Management Methods

    /**
     * Displays the list of users.
     * @param string|null $search_term Optional search term from URL path.
     */
    public function manageUsers($search_term = null) // Added $search_term parameter
    {
        $searchTerm = $_GET['search'] ?? $search_term ?? ''; // Prioritize GET, then path param
        $searchTerm = trim($searchTerm);

        $users = $this->userModel->getAllUsers($searchTerm);

        $data = [
            'title' => 'Manage Users',
            'users' => $users,
            'searchTerm' => $searchTerm,
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
        ];
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('admin/users/index', $data);
    }

    /**
     * Toggles the ban status of a user.
     * @param int $userId The ID of the user to ban/unban.
     */
    public function toggleUserBan($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method for user ban toggle.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        // Prevent banning/deleting oneself if user_id is the current admin's ID
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $_SESSION['error'] = 'You cannot ban or delete your own admin account.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        if ($user['is_banned']) {
            if ($this->userModel->unbanUser($userId)) {
                $_SESSION['success'] = 'User ' . htmlspecialchars($user['username']) . ' has been unbanned.';
            } else {
                $_SESSION['error'] = 'Failed to unban user ' . htmlspecialchars($user['username']) . '.';
            }
        } else {
            if ($this->userModel->banUser($userId)) {
                $_SESSION['success'] = 'User ' . htmlspecialchars($user['username']) . ' has been banned.';
            } else {
                $_SESSION['error'] = 'Failed to ban user ' . htmlspecialchars($user['username']) . '.';
            }
        }

        header('Location: /pcbuild/public/admin/users');
        exit();
    }

    /**
     * Deletes a user account.
     * @param int $userId The ID of the user to delete.
     */
    public function deleteUserAccount($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method for user deletion.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        // Prevent banning/deleting oneself if user_id is the current admin's ID
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $_SESSION['error'] = 'You cannot ban or delete your own admin account.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        if ($this->userModel->deleteUser($userId)) {
            $_SESSION['success'] = 'User ' . htmlspecialchars($user['username']) . ' and all associated data have been deleted.';
        } else {
            $_SESSION['error'] = 'Failed to delete user ' . htmlspecialchars($user['username']) . '.';
        }

        header('Location: /pcbuild/public/admin/users');
        exit();
    }

    /**
     * Displays a user's order history.
     * @param int $userId The ID of the user whose orders to display.
     */
    public function viewUserOrders($userId)
    {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('Location: /pcbuild/public/admin/users');
            exit();
        }

        $orders = $this->orderModel->getOrdersByUserId($userId);

        $data = [
            'title' => 'Order History for ' . htmlspecialchars($user['username']),
            'user' => $user,
            'orders' => $orders
        ];
        $this->view('admin/users/orders', $data);
    }
}