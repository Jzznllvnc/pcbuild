<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/User.php';

class AuthController extends BaseController
{
    protected $userModel;

    /**
     * Constructor for AuthController.
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->userModel = new User($pdo);
    }

    public function showLogin()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = [
            'title' => 'Login to Your Account',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
        ];

        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/login', $data);
    }

    public function login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';

            error_log("Auth Controller: Login attempt for identifier: '{$identifier}'");

            if (empty($identifier) || empty($password)) {
                $_SESSION['error'] = 'Please enter both username/email and password.';
                error_log("Auth Controller: Empty identifier or password.");
                header('Location: ' . BASE_URL . '/login');
                exit();
            }

            $user = $this->userModel->findByUsernameOrEmail($identifier);
            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_banned']) {
                    $_SESSION['error'] = 'Your account has been banned. Please contact support.';
                    error_log("Auth Controller: Login failed: Banned user '{$identifier}' attempted to log in.");
                    header('Location: ' . BASE_URL . '/login');
                    exit();
                }

                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = (bool)$user['is_admin'];
                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['username']) . '!';
                $_SESSION['sync_cart_on_load'] = true;

                // Update last login timestamp
                $this->userModel->updateLastLogin($user['id']);

                error_log("Auth Controller: Login successful for user: " . $user['username'] . " (Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . ")");

                if ($_SESSION['is_admin']) {
                    header('Location: ' . BASE_URL . '/admin/dashboard');
                } else {
                    header('Location: ' . BASE_URL . '/home');
                }
                exit();
            } else {
                // Login failed
                $_SESSION['error'] = 'Invalid username/email or password.';
                error_log("Auth Controller: Login failed for identifier: '{$identifier}' - User not found or password mismatch.");
                header('Location: ' . BASE_URL . '/login');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/login');
            exit();
        }
    }

    public function showRegister()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = [
            'title' => 'Create New Account',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
        ];
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/register', $data);
    }

    public function register()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            error_log("Auth Controller: Registration attempt for username='{$username}', email='{$email}'");

            // Basic validation
            if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                $_SESSION['error'] = 'All fields are required.';
                error_log("Auth Controller: Registration failed: Missing fields.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Invalid email format.';
                error_log("Auth Controller: Registration failed: Invalid email format.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match.';
                error_log("Auth Controller: Registration failed: Passwords do not match.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                error_log("Auth Controller: Registration failed: Password too short.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }

            if ($this->userModel->findByUsernameOrEmail($username)) {
                $_SESSION['error'] = 'Username already exists. Please choose a different one.';
                error_log("Auth Controller: Registration failed: Username '{$username}' already exists.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }
             if ($this->userModel->findByUsernameOrEmail($email)) {
                $_SESSION['error'] = 'Email already exists. Please choose a different one.';
                error_log("Auth Controller: Registration failed: Email '{$email}' already exists.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }


            // Hash the password securely
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            error_log("Auth Controller: Password hashed successfully. Hash: '{$passwordHash}'");

            // Create the user
            $userId = $this->userModel->createUser($username, $email, $passwordHash);

            if ($userId) {
                $_SESSION['success'] = 'Account created successfully! Please log in.';
                error_log("Auth Controller: User registration successful. User ID: {$userId}");
                header('Location: ' . BASE_URL . '/login');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                error_log("Auth Controller: User registration failed, createUser returned false.");
                header('Location: ' . BASE_URL . '/register');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/register');
            exit();
        }
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        error_log("Auth Controller: User logging out.");
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        error_log("Auth Controller: Session destroyed, redirecting to home.");
        header('Location: ' . BASE_URL . '/home'); 
        exit();
    }

    public function showForgotPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha_code = '';
        for ($i = 0; $i < 6; $i++) {
            $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
        }
        $_SESSION['captcha_code'] = $captcha_code;

        $data = [
            'title' => 'Reset Your Password',
            'captcha_code' => $captcha_code,
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
        ];
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/forgot_password', $data);
    }

    public function processForgotPasswordRequest()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? '');
            $captcha_input = trim($_POST['captcha_input'] ?? '');
            $stored_captcha_code = $_SESSION['captcha_code'] ?? null;

            if (empty($identifier) || empty($captcha_input)) {
                $_SESSION['error'] = 'All fields are required.';
                header('Location: ' . BASE_URL . '/forgot-password');
                exit();
            }

            // Case-insensitive comparison for captcha
            if (strtoupper($captcha_input) !== strtoupper($stored_captcha_code)) {
                $_SESSION['error'] = 'Incorrect code. Please try again.';
                unset($_SESSION['captcha_code']);
                header('Location: ' . BASE_URL . '/forgot-password');
                exit();
            }

            $user = $this->userModel->findByUsernameOrEmail($identifier);

            if ($user) {
                $_SESSION['password_reset_user_id'] = $user['id'];
                unset($_SESSION['captcha_code']);

                $_SESSION['success'] = 'Code verified. Please set your new password.';
                header('Location: ' . BASE_URL . '/reset-password');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid username/email or incorrect code. Please try again.';
                unset($_SESSION['captcha_code']);
                header('Location: ' . BASE_URL . '/forgot-password');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }
    }

    public function showResetPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['password_reset_user_id'])) {
            $_SESSION['error'] = 'Access denied. Please start the password reset process from the "Forgot Password" page.';
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }

        $data = [
            'title' => 'Set New Password',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            'error_code_mismatch' => false
        ];
        
        // If accessed directly without session variable, set an error
        if (empty($_SESSION['password_reset_user_id'])) {
            $data['error'] = 'Invalid access to password reset. Please go back to the forgot password page.';
            $data['error_code_mismatch'] = true;
        }

        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/reset_password', $data);
    }

    public function resetPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['password_reset_user_id'])) {
            $_SESSION['error'] = 'Access denied. Please start the password reset process from the "Forgot Password" page.';
            header('Location: ' . BASE_URL . '/forgot-password');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['password_reset_user_id'];
            $newPassword = $_POST['password'] ?? '';
            $confirmNewPassword = $_POST['confirm_password'] ?? '';

            // 1. Validate input
            if (empty($newPassword) || empty($confirmNewPassword)) {
                $_SESSION['error'] = 'All fields are required.';
                header('Location: ' . BASE_URL . '/reset-password');
                exit();
            }

            if ($newPassword !== $confirmNewPassword) {
                $_SESSION['error'] = 'New passwords do not match.';
                header('Location: ' . BASE_URL . '/reset-password');
                exit();
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                header('Location: ' . BASE_URL . '/reset-password');
                exit();
            }

            // 2. Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updated = $this->userModel->updatePassword($userId, $hashedPassword);

            if ($updated) {
                unset($_SESSION['password_reset_user_id']);
                $_SESSION['success'] = 'Your password has been reset successfully. You can now log in with your new password.';
                header('Location: ' . BASE_URL . '/login');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to reset password. Please try again.';
                header('Location: ' . BASE_URL . '/reset-password');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/reset-password');
            exit();
        }
    }
}