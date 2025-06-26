<?php

// Make sure to include the BaseController and the User model
require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/User.php';

class AuthController extends BaseController
{
    protected $userModel;

    /**
     * Constructor for AuthController.
     * Initializes the User model with the PDO connection.
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo); // Call the parent constructor to set $this->pdo
        $this->userModel = new User($pdo); // Instantiate the User model
    }

    /**
     * Displays the login form.
     */
    public function showLogin()
    {
        // Start session if not already started (needed for flash messages)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = [
            'title' => 'Login to Your Account',
            'error' => $_SESSION['error'] ?? null, // Get error messages from session
            'success' => $_SESSION['success'] ?? null, // Get success messages from session
        ];

        // Clear session messages after displaying them
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/login', $data);
    }

    /**
     * Handles login form submission.
     */
    public function login()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? ''); // username or email
            $password = $_POST['password'] ?? '';

            error_log("Auth Controller: Login attempt for identifier: '{$identifier}'");

            if (empty($identifier) || empty($password)) {
                $_SESSION['error'] = 'Please enter both username/email and password.';
                error_log("Auth Controller: Empty identifier or password.");
                header('Location: /pcbuild/public/login');
                exit();
            }

            $user = $this->userModel->findByUsernameOrEmail($identifier);

            // Check if user exists and password is correct, AND if the user is not banned
            if ($user && password_verify($password, $user['password'])) {
                if ($user['is_banned']) {
                    $_SESSION['error'] = 'Your account has been banned. Please contact support.';
                    error_log("Auth Controller: Login failed: Banned user '{$identifier}' attempted to log in.");
                    header('Location: /pcbuild/public/login');
                    exit();
                }

                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = (bool)$user['is_admin']; // Store admin status
                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['username']) . '!';

                // Add this line to signal cart sync on next page load
                $_SESSION['sync_cart_on_load'] = true;

                // Update last login timestamp
                $this->userModel->updateLastLogin($user['id']);

                error_log("Auth Controller: Login successful for user: " . $user['username'] . " (Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . ")");
                
                // Redirect based on admin status
                if ($_SESSION['is_admin']) {
                    header('Location: /pcbuild/public/admin/dashboard');
                } else {
                    header('Location: /pcbuild/public/home'); // Redirect to dashboard or home
                }
                exit();
            } else {
                // Login failed
                $_SESSION['error'] = 'Invalid username/email or password.';
                error_log("Auth Controller: Login failed for identifier: '{$identifier}' - User not found or password mismatch.");
                header('Location: /pcbuild/public/login');
                exit();
            }
        } else {
            // If accessed directly via GET, redirect to show login form
            header('Location: /pcbuild/public/login');
            exit();
        }
    }

    /**
     * Displays the registration form.
     */
    public function showRegister()
    {
        // Start session if not already started
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

    /**
     * Handles registration form submission.
     */
    public function register()
    {
        // Start session if not already started
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
                header('Location: /pcbuild/public/register');
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Invalid email format.';
                error_log("Auth Controller: Registration failed: Invalid email format.");
                header('Location: /pcbuild/public/register');
                exit();
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match.';
                error_log("Auth Controller: Registration failed: Passwords do not match.");
                header('Location: /pcbuild/public/register');
                exit();
            }

            if (strlen($password) < 6) { // Minimum password length
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                error_log("Auth Controller: Registration failed: Password too short.");
                header('Location: /pcbuild/public/register');
                exit();
            }

            // Check if username or email already exists
            if ($this->userModel->findByUsernameOrEmail($username)) {
                $_SESSION['error'] = 'Username already exists. Please choose a different one.';
                error_log("Auth Controller: Registration failed: Username '{$username}' already exists.");
                header('Location: /pcbuild/public/register');
                exit();
            }
             if ($this->userModel->findByUsernameOrEmail($email)) {
                $_SESSION['error'] = 'Email already exists. Please choose a different one.';
                error_log("Auth Controller: Registration failed: Email '{$email}' already exists.");
                header('Location: /pcbuild/public/register');
                exit();
            }


            // Hash the password securely
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            error_log("Auth Controller: Password hashed successfully. Hash: '{$passwordHash}'");

            // Create the user
            $userId = $this->userModel->createUser($username, $email, $passwordHash);

            if ($userId) {
                // Add this line to signal cart sync on next page load
                // $_SESSION['sync_cart_on_load'] = true;

                $_SESSION['success'] = 'Account created successfully! Please log in.';
                error_log("Auth Controller: User registration successful. User ID: {$userId}");
                header('Location: /pcbuild/public/login');
                exit();
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                error_log("Auth Controller: User registration failed, createUser returned false.");
                header('Location: /pcbuild/public/register');
                exit();
            }

        } else {
            header('Location: /pcbuild/public/register'); // If accessed directly via GET
            exit();
        }
    }

    /**
     * Handles user logout.
     */
    public function logout()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        error_log("Auth Controller: User logging out.");

        // Unset all of the session variables.
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        error_log("Auth Controller: Session destroyed, redirecting to home.");
        header('Location: /pcbuild/public/home'); // Redirect to homepage or login page
        exit();
    }

    /**
     * Displays the forgot password form with a generated captcha code.
     */
    public function showForgotPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Generate a random code for the user to type
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Exclude I, O, 0, 1 for clarity
        $captcha_code = '';
        for ($i = 0; $i < 6; $i++) {
            $captcha_code .= $characters[rand(0, strlen($characters) - 1)];
        }
        $_SESSION['captcha_code'] = $captcha_code; // Store in session for verification

        $data = [
            'title' => 'Reset Your Password',
            'captcha_code' => $captcha_code, // Pass to the view
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
        ];
        unset($_SESSION['error']);
        unset($_SESSION['success']);

        $this->view('auth/forgot_password', $data);
    }

    /**
     * Handles the submission of the forgot password form (code verification).
     * If the code is correct and user is found, redirects to the reset password page.
     */
    public function processForgotPasswordRequest()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifier = trim($_POST['identifier'] ?? '');
            $captcha_input = trim($_POST['captcha_input'] ?? '');
            $stored_captcha_code = $_SESSION['captcha_code'] ?? null;

            // 1. Validate inputs and captcha
            if (empty($identifier) || empty($captcha_input)) {
                $_SESSION['error'] = 'All fields are required.';
                header('Location: /pcbuild/public/forgot-password');
                exit();
            }

            // Case-insensitive comparison for captcha
            if (strtoupper($captcha_input) !== strtoupper($stored_captcha_code)) {
                $_SESSION['error'] = 'Incorrect code. Please try again.';
                // Regenerate captcha on error for security
                unset($_SESSION['captcha_code']);
                header('Location: /pcbuild/public/forgot-password');
                exit();
            }

            // 2. Find the user by username or email
            $user = $this->userModel->findByUsernameOrEmail($identifier);

            if ($user) {
                // Captcha correct and user found, store user ID in session for password reset
                $_SESSION['password_reset_user_id'] = $user['id'];
                unset($_SESSION['captcha_code']); // Clear captcha once used

                $_SESSION['success'] = 'Code verified. Please set your new password.';
                header('Location: /pcbuild/public/reset-password');
                exit();
            } else {
                // User not found, but to prevent enumeration, act as if code was wrong or just generic error
                $_SESSION['error'] = 'Invalid username/email or incorrect code. Please try again.';
                unset($_SESSION['captcha_code']);
                header('Location: /pcbuild/public/forgot-password');
                exit();
            }
        } else {
            header('Location: /pcbuild/public/forgot-password');
            exit();
        }
    }

    /**
     * Displays the password reset form.
     * Accessible only if user ID is in session (from successful code verification).
     */
    public function showResetPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if a user ID is set in the session, meaning they've passed the code verification
        if (!isset($_SESSION['password_reset_user_id'])) {
            $_SESSION['error'] = 'Access denied. Please start the password reset process from the "Forgot Password" page.';
            header('Location: /pcbuild/public/forgot-password');
            exit();
        }

        $data = [
            'title' => 'Set New Password',
            'error' => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null,
            // Flag to indicate if there was an initial error preventing form display (e.g., direct access)
            'error_code_mismatch' => false // Initialize this, will be set true if direct access to reset-password
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

    /**
     * Handles the submission of the password reset form.
     * Updates the user's password and clears the session variable.
     */
    public function resetPassword()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Ensure user ID is present in session
        if (!isset($_SESSION['password_reset_user_id'])) {
            $_SESSION['error'] = 'Access denied. Please start the password reset process from the "Forgot Password" page.';
            header('Location: /pcbuild/public/forgot-password');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['password_reset_user_id'];
            $newPassword = $_POST['password'] ?? '';
            $confirmNewPassword = $_POST['confirm_password'] ?? '';

            // 1. Validate input
            if (empty($newPassword) || empty($confirmNewPassword)) {
                $_SESSION['error'] = 'All fields are required.';
                header('Location: /pcbuild/public/reset-password');
                exit();
            }

            if ($newPassword !== $confirmNewPassword) {
                $_SESSION['error'] = 'New passwords do not match.';
                header('Location: /pcbuild/public/reset-password');
                exit();
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters long.';
                header('Location: /pcbuild/public/reset-password');
                exit();
            }

            // 2. Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updated = $this->userModel->updatePassword($userId, $hashedPassword);

            if ($updated) {
                // Password updated successfully, clear the session variable
                unset($_SESSION['password_reset_user_id']);
                $_SESSION['success'] = 'Your password has been reset successfully. You can now log in with your new password.';
                header('Location: /pcbuild/public/login');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to reset password. Please try again.';
                header('Location: /pcbuild/public/reset-password');
                exit();
            }
        } else {
            // If accessed directly via GET, redirect to the show method
            header('Location: /pcbuild/public/reset-password');
            exit();
        }
    }
}