<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/User.php';
require_once BASE_PATH . 'app/models/Order.php';

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

    public function orderhistory()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your orders.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username'];

        // Fetch user's orders
        $userOrders = $this->orderModel->getOrdersByUserId($userId);

        $data = [
            'title' => 'My Orders',
            'username' => $username,
            'orders' => $userOrders
        ];

        $this->view('user/orderhistory', $data);
    }

    public function profile()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your profile.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('Location: ' . BASE_URL . '/home');
            exit();
        }

        unset($_SESSION['success']);
        unset($_SESSION['error']);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'success' => null,
            'error' => null,
        ];

        $this->view('user/profile', $data);
    }

    public function updateGeneralInformation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your profile.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $newUsername = trim($_POST['username'] ?? '');

        // Basic validation
        if (empty($newUsername)) {
            $_SESSION['error'] = 'Username cannot be empty.';
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }

        $existingUser = $this->userModel->findByUsernameOrEmail($newUsername);
        if ($existingUser && $existingUser['id'] != $userId) {
            $_SESSION['error'] = 'Username already taken. Please choose a different one.';
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }

        $updated = $this->userModel->updateUsername($userId, $newUsername);

        if ($updated) {
            $_SESSION['username'] = $newUsername;
            header('Location: ' . BASE_URL . '/profile?success_msg=' . urlencode('Profile updated successfully!'));
            exit();
        } else {
            header('Location: ' . BASE_URL . '/profile?error_msg=' . urlencode('Failed to update profile. Please try again.'));
            exit();
        }

        header('Location: ' . BASE_URL . '/profile');
        exit();
    }

    public function updatePhoneNumber()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your phone number.';
            header('Location: ' . BASE_URL . '/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $phoneNumber = trim($_POST['phone_number'] ?? '');

        if (!empty($phoneNumber) && !preg_match('/^[0-9]{10}$/', $phoneNumber)) {
            $_SESSION['error'] = 'Please enter a valid 10-digit Philippine mobile number (e.g., 9123456789).';
            header('Location: ' . BASE_URL . '/profile');
            exit();
        }

        $phoneNumberToStore = empty($phoneNumber) ? null : $phoneNumber;
        $updated = $this->userModel->updatePhoneNumber($userId, $phoneNumberToStore);

        if ($updated) {
            header('Location: ' . BASE_URL . '/profile?success_msg=' . urlencode('Phone number updated successfully!'));
            exit();
        } else {
            header('Location: ' . BASE_URL . '/profile?error_msg=' . urlencode('Failed to update phone number. Please try again.'));
            exit();
        }

        header('Location: ' . BASE_URL . '/profile');
        exit();
    }

    public function clearNewOrderNotificationApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['new_order_notification']);
            $this->jsonResponse(['success' => true, 'message' => 'Notification cleared.']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in.'], 401);
        }
    }
}