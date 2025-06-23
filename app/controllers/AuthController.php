<?php

// Make sure to include the BaseController and the User model
require_once BASE_PATH . 'app/controllers/BaseController.php';

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

            if ($user && password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = (bool)$user['is_admin']; // Store admin status
                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['username']) . '!';

                error_log("Auth Controller: Login successful for user: " . $user['username'] . " (Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . ")");
                header('Location: /pcbuild/public/home'); // Redirect to dashboard or home
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
}
