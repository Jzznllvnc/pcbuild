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
        // Calculate dates for "last 30 days" for summary cards
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        // Fetch Dashboard Data for top cards
        $newOrders = $this->orderModel->getTotalOrders($thirtyDaysAgo);
        $totalIncome = $this->orderModel->getTotalRevenue($thirtyDaysAgo);
        $totalCustomers = $this->userModel->getTotalUsersRegistered(); // Total registered users
        // Assuming "Pending Delivery" from the screenshot corresponds to current pending orders, not just new users
        $pendingDelivery = $this->orderModel->getPendingOrders($thirtyDaysAgo);

        // Placeholder for "Total Expense" and "New User" as they require more complex data or separate tables
        $totalExpense = 24567.00; // Placeholder value
        $newUser = 34567; // Placeholder for new users or existing total customers if you prefer

        // Fetch monthly revenue data for Yearly Stats and Sales/Revenue charts
        $currentYear = date('Y');
        $monthlyRevenueData = $this->orderModel->getMonthlyRevenue($currentYear);
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyDataValues = array_values($monthlyRevenueData);

        // Calculate total for yearly stats (sum of all months)
        $yearlyStatsTotal = array_sum($monthlyDataValues);

        $data = [
            'title' => 'Admin Dashboard',
            'newOrders' => $newOrders,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'newUser' => $totalCustomers, // Using total customers for now as a general user metric
            'yearlyStatsTotal' => $yearlyStatsTotal,
            'monthlyLabels' => json_encode($monthlyLabels),
            'monthlyDataValues' => json_encode($monthlyDataValues),
        ];
        $this->view('admin/dashboard', $data);
    }

    /**
     * Lists all products for admin management.
     */
    public function products() // Method renamed from listProducts to products
    {
        // Ensure session messages are cleared before being potentially displayed on this page
        // This prevents "Welcome back" from showing on this list
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $products = $this->productModel->getProducts(1000, 0);

        $data = [
            'title' => 'Manage Products',
            'products' => $products,
            //'success' => $_SESSION['success'] ?? null,
            //'error' => $_SESSION['error'] ?? null
        ];
        //unset($_SESSION['success']);
        //unset($_SESSION['error']);

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
            header('Location: /pcbuild/public/admin/products?success_msg=' . urlencode('Product "' . htmlspecialchars($data['name']) . '" added successfully!'));
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
            header('Location: /pcbuild/public/admin/products?success_msg=' . urlencode('Product "' . htmlspecialchars($data['name']) . '" updated successfully!'));
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
        // Set header to return JSON response
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method for deletion.']);
            http_response_code(400); // Bad Request
            exit();
        }

        // Check if user is logged in and is admin
        // This is a redundant check if the constructor already handles it, but good for specific action
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            echo json_encode(['error' => 'Authentication required or not an admin.']);
            http_response_code(403); // Forbidden
            exit();
        }

        $deleted = $this->productModel->deleteProduct($id);

        if ($deleted === 'referenced') {
            $_SESSION['error'] = 'Cannot delete product: It is linked to existing orders.'; // Set an error message
            header('Location: /pcbuild/public/admin/products'); // Redirect back to product list
            exit();
        } elseif ($deleted) {
            // On success, redirect with a success message in the URL
            header('Location: /pcbuild/public/admin/products?success_msg=' . urlencode('Product deleted successfully!'));
            exit();
        } else {
            $_SESSION['error'] = 'Failed to delete product. It might not exist or a database error occurred.'; // Set an error message
            header('Location: /pcbuild/public/admin/products'); // Redirect back to product list
            exit();
        }
    }


    // User Management Methods

    /**
     * Displays the list of users.
     * @param string|null $search_term Optional term to search by username or email.
     */
    public function manageUsers($search_term = null) // Added $search_term parameter
    {

        // Ensure session messages are cleared before being potentially displayed on this page
        // This prevents "Welcome back" from showing on this list
        unset($_SESSION['success']);
        unset($_SESSION['error']);

        $searchTerm = $_GET['search'] ?? $search_term ?? ''; // Prioritize GET, then path param
        $searchTerm = trim($searchTerm);

        $users = $this->userModel->getAllUsers($searchTerm);

        $data = [
            'title' => 'Manage Users',
            'users' => $users,
            //'error' => $_SESSION['error'] ?? null,
            //'success' => $_SESSION['success'] ?? null,
        ];
        //unset($_SESSION['error']);
        //unset($_SESSION['success']);

        $this->view('admin/users/index', $data);
    }

    /**
     * Toggles the ban status of a user.
     * @param int $userId The ID of the user to ban/unban.
     */
    public function toggleUserBan($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
            return;
        }

        // Ensure user is admin (redundant with constructor, but good for specific actions)
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $this->jsonResponse(['success' => false, 'error' => 'Authentication required or not an admin.'], 403);
            return;
        }

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $this->jsonResponse(['success' => false, 'error' => 'User not found.'], 404);
            return;
        }

        // Prevent banning/deleting oneself if user_id is the current admin's ID
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $this->jsonResponse(['success' => false, 'error' => 'You cannot ban or delete your own admin account.'], 403);
            return;
        }

        if ($user['is_banned']) {
            if ($this->userModel->unbanUser($userId)) {
                $this->jsonResponse(['success' => true, 'message' => 'User ' . htmlspecialchars($user['username']) . ' has been unbanned.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to unban user ' . htmlspecialchars($user['username']) . '.'], 500);
            }
        } else {
            if ($this->userModel->banUser($userId)) {
                $this->jsonResponse(['success' => true, 'message' => 'User ' . htmlspecialchars($user['username']) . ' has been banned.']);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to ban user ' . htmlspecialchars($user['username']) . '.'], 500);
            }
        }
    }

    /**
     * Deletes a user account.
     * @param int $userId The ID of the user to delete.
     */
    public function deleteUserAccount($userId)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method.'], 405);
            return;
        }

        // Ensure user is admin (redundant with constructor, but good for specific actions)
        if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $this->jsonResponse(['success' => false, 'error' => 'Authentication required or not an admin.'], 403);
            return;
        }

        $user = $this->userModel->findById($userId);

        if (!$user) {
            $this->jsonResponse(['success' => false, 'error' => 'User not found.'], 404);
            return;
        }

        // Prevent banning/deleting oneself if user_id is the current admin's ID
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $userId) {
            $this->jsonResponse(['success' => false, 'error' => 'You cannot ban or delete your own admin account.'], 403);
            return;
        }

        if ($this->userModel->deleteUser($userId)) {
            $this->jsonResponse(['success' => true, 'message' => 'User ' . htmlspecialchars($user['username']) . ' and all associated data have been deleted.']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to delete user ' . htmlspecialchars($user['username']) . '.'], 500);
        }
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