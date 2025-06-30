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

    /**
     * Displays the user orders with personal information and order history.
     */
    public function orderhistory()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your orders.';
            header('Location: /pcbuild/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $username = $_SESSION['username']; // Already stored in session from login

        // Fetch user's orders
        $userOrders = $this->orderModel->getOrdersByUserId($userId);

        $data = [
            'title' => 'My Orders',
            'username' => $username,
            'orders' => $userOrders
        ];

        $this->view('user/orderhistory', $data);
    }

    /**
     * Displays the user profile configuration page.
     */
    public function profile()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to view your profile.';
            header('Location: /pcbuild/login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'User not found.';
            header('Location: /pcbuild/home');
            exit();
        }

        // Clear these session messages BEFORE passing them to the view
        unset($_SESSION['success']);
        unset($_SESSION['error']);

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'success' => null, // Now explicitly null or whatever you want, as it's unset
            'error' => null,   // Now explicitly null or whatever you want, as it's unset
        ];

        $this->view('user/profile', $data);
    }

    /**
     * Handles the update of general user information (username only).
     */
    public function updateGeneralInformation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your profile.';
            header('Location: /pcbuild/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: /pcbuild/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $newUsername = trim($_POST['username'] ?? '');

        // Basic validation
        if (empty($newUsername)) {
            $_SESSION['error'] = 'Username cannot be empty.';
            header('Location: /pcbuild/profile');
            exit();
        }

        // Check if the new username already exists for another user
        $existingUser = $this->userModel->findByUsernameOrEmail($newUsername);
        if ($existingUser && $existingUser['id'] != $userId) {
            $_SESSION['error'] = 'Username already taken. Please choose a different one.';
            header('Location: /pcbuild/profile');
            exit();
        }

        // Attempt to update the user's username
        $updated = $this->userModel->updateUsername($userId, $newUsername);

        if ($updated) {
            $_SESSION['username'] = $newUsername; // Update session username if changed
            header('Location: /pcbuild/profile?success_msg=' . urlencode('Profile updated successfully!'));
            exit();
        } else {
            header('Location: /pcbuild/profile?error_msg=' . urlencode('Failed to update profile. Please try again.'));
            exit();
        }

        header('Location: /pcbuild/profile');
        exit();
    }

    /**
     * Handles the update of the user's phone number.
     */
    public function updatePhoneNumber()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to update your phone number.';
            header('Location: /pcbuild/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: /pcbuild/profile');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $phoneNumber = trim($_POST['phone_number'] ?? '');

        // Specific validation for 10-digit Philippine numbers if not empty
        if (!empty($phoneNumber) && !preg_match('/^[0-9]{10}$/', $phoneNumber)) { // Changed to 10 digits
            $_SESSION['error'] = 'Please enter a valid 10-digit Philippine mobile number (e.g., 9123456789).';
            header('Location: /pcbuild/profile');
            exit();
        }

        // Set to null if empty, so it stores NULL in DB
        $phoneNumberToStore = empty($phoneNumber) ? null : $phoneNumber;

        $updated = $this->userModel->updatePhoneNumber($userId, $phoneNumberToStore);

        if ($updated) {
            header('Location: /pcbuild/profile?success_msg=' . urlencode('Phone number updated successfully!'));
            exit();
        } else {
            header('Location: /pcbuild/profile?error_msg=' . urlencode('Failed to update phone number. Please try again.'));
            exit();
        }

        header('Location: /pcbuild/profile');
        exit();
    }

    public function clearNewOrderNotificationApi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Only clear if the user is logged in
        if (isset($_SESSION['user_id'])) {
            unset($_SESSION['new_order_notification']);
            $this->jsonResponse(['success' => true, 'message' => 'Notification cleared.']);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Not logged in.'], 401);
        }
    }
}