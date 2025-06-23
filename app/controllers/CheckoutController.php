<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';

class CheckoutController extends BaseController
{
    protected $orderModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->orderModel = new Order($pdo);
    }

    /**
     * Displays the checkout page.
     * Expects cart items data from JavaScript via URL parameters or hidden fields if we had them.
     * For now, we'll assume cart items are handled on frontend.
     */
    public function index()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to proceed to checkout.';
            header('Location: /pcbuild/public/login');
            exit();
        }

        // In a real application, you might pass cart data from JavaScript
        // through a hidden form field or session, or re-fetch product prices
        // to prevent tampering. For now, we'll rely on the frontend for display
        // and process the items received from the post request in processOrder().
        $data = [
            'title' => 'Complete Your Order'
        ];

        $this->view('checkout/index', $data);
    }

    /**
     * Processes the simulated order/payment.
     * Expects POST data with payment method and cart items JSON.
     */
    public function processOrder()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'You must be logged in to process your order.'; //
            header('Location: /pcbuild/public/login'); //
            exit(); //
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Invalid request method.';
            header('Location: /pcbuild/public/cart');
            exit();
        }

        $paymentMethod = $_POST['payment_method'] ?? '';
        $cartItemsJson = $_POST['cart_items_json'] ?? '[]'; // JSON string of cart items
        $totalAmount = (float)($_POST['total_amount'] ?? 0);

        $cartItems = json_decode($cartItemsJson, true);

        if (empty($paymentMethod) || !in_array($paymentMethod, ['GCash', 'PayPal'])) {
            $_SESSION['error'] = 'Please select a valid payment method.';
            header('Location: /pcbuild/public/checkout');
            exit();
        }

        if (empty($cartItems) || !is_array($cartItems)) {
            $_SESSION['error'] = 'Your cart is empty or invalid.';
            header('Location: /pcbuild/public/cart');
            exit();
        }

        // Get user ID if logged in, otherwise null for guest checkout
        $userId = $_SESSION['user_id'] ?? null;

        // --- Simulate Payment Process ---
        // In a real scenario, you would integrate with GCash/PayPal APIs here.
        // This could involve redirecting the user, handling callbacks, etc.
        $paymentSuccess = true; // Assume success for simulation

        if ($paymentSuccess) {
            $orderId = $this->orderModel->createOrder($userId, $totalAmount, $paymentMethod, $cartItems);

            if ($orderId) {
                $_SESSION['last_order_id'] = $orderId; // Store order ID for success page
                // We'll add JS to clear cart after successful processing
                header('Location: /pcbuild/public/checkout/success');
                exit();
            } else {
                $_SESSION['error'] = 'There was an issue saving your order. Please try again.';
                header('Location: /pcbuild/public/checkout');
                exit();
            }
        } else {
            // Simulated payment failed
            $_SESSION['error'] = 'Payment failed. Please try again or choose a different method.';
            header('Location: /pcbuild/public/checkout');
            exit();
        }
    }

    /**
     * Displays the order success/confirmation page.
     */
    public function success()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $orderId = $_SESSION['last_order_id'] ?? null;
        unset($_SESSION['last_order_id']); // Clear it after use

        $order = null;
        if ($orderId) {
            $order = $this->orderModel->getOrderById($orderId);
        }

        $data = [
            'title' => 'Order Confirmed!',
            'order' => $order
        ];

        $this->view('checkout/success', $data);
    }
}