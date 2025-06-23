<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';

class CartController extends BaseController
{
    /**
     * Displays the shopping cart page.
     */
    public function index()
    {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $isLoggedIn = isset($_SESSION['user_id']); // Check if user is logged in

        $data = [
            'title' => 'Your Shopping Cart',
            'isLoggedIn' => $isLoggedIn // Pass login status to the view
            // Cart items will be loaded dynamically by JavaScript
        ];

        $this->view('cart/index', $data);
    }
}