<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';

class AdminController extends BaseController
{
    protected $productModel;
    protected $userModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->productModel = new Product($pdo);
        $this->userModel = new User($pdo); // Initialize User model
        $this->authorizeAdmin(); // Authorize admin on every admin page access
    }

    /**
     * Authorizes admin access. Redirects to login if not admin.
     */
    protected function authorizeAdmin()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            $_SESSION['error'] = 'Access denied. You must be an administrator.';
            header('Location: /pcbuild/public/login');
            exit();
        }
    }

    /**
     * Displays the main admin dashboard.
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'username' => $_SESSION['username'] ?? 'Admin' // Should be set by authorizeAdmin
        ];
        $this->view('admin/dashboard', $data);
    }

    /**
     * Lists all products for admin management.
     */
    public function listProducts()
    {
        $products = $this->productModel->getAllProducts(); // Or getFilteredProducts for admin search

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
    public function createProductForm()
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
    public function createProduct()
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


        $productId = $this->productModel->createProduct($data);

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
    public function editProductForm($id)
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
    public function updateProduct($id)
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

        $updated = $this->productModel->updateProduct($id, $data);

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
}
