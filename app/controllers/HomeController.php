<?php

// Make sure to include the BaseController
require_once BASE_PATH . 'app/controllers/BaseController.php';

class HomeController extends BaseController
{
    /**
     * Handles the display of the home page.
     */
    public function index()
    {
        // You could fetch data from the database here if needed for the home page
        // Example: $latestProducts = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 3")->fetchAll();

        // Data to pass to the view
        $data = [
            'title' => 'Welcome to PCBuilder Pro!',
            'message' => 'Your ultimate destination for building custom PCs.'
        ];

        // Render the home view
        $this->view('home', $data);
    }
}
