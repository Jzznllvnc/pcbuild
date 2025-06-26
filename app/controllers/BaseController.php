<?php

class BaseController
{
    protected $pdo; // Property to hold the PDO database connection

    /**
     * Constructor for the BaseController.
     * All child controllers will inherit this constructor.
     * @param PDO $pdo The PDO database connection object.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * A helper method to render a view.
     * This now directly includes the header, view file, and footer.
     * @param string $viewPath Path to the view file (e.g., 'home', 'products/list')
     * @param array $data Data to pass to the view
     */
    protected function view($viewPath, $data = [])
    {
        // Extract data into local variables for the view
        extract($data);

        // Convert viewPath to file path, e.g., 'home' -> 'app/views/home.php'
        $filePath = BASE_PATH . 'app/views/' . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($filePath)) {
            die("View file not found: " . $filePath);
        }

        // Include the header and actual view file, then the footer
        require_once BASE_PATH . 'includes/header.php';
        require_once $filePath;
        require_once BASE_PATH . 'includes/footer.php';
    }

    /**
     * Helper method to send a JSON response and terminate script execution.
     * @param array $data The data array to be encoded as JSON.
     * @param int $statusCode The HTTP status code to send (default: 200 OK).
     */
    protected function jsonResponse(array $data, int $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data);
        exit(); // Crucially stops further script execution and output
    }
}