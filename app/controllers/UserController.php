<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';

class UserController extends BaseController
{
    protected $userModel;
    protected $orderModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userModel = new User($pdo);
        $this->orderModel = new Order($pdo);
    }

    /**
     * Displays the user dashboard with personal information and order history.
     */
    public function dashboard()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your dashboard.';
            header('Location: /pcbuild/public/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username']; // Already stored in session from login

        // Fetch user's orders
        $userOrders = $this->orderModel->getOrdersByUserId($userId);

        $data = [
            'title' => 'My Dashboard',
            'username' => $username,
            'orders' => $userOrders
        ];

        $this->view('user/dashboard', $data);
    }

    // You can add other user-specific methods here later (e.g., update profile)
}
