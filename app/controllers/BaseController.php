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
     * This simply calls the static Router::view method.
     * @param string $viewPath Path to the view file (e.g., 'home', 'products/list')
     * @param array $data Data to pass to the view
     */
    protected function view($viewPath, $data = [])
    {
        // Include the Router class if it hasn't been already
        if (!class_exists('Router')) {
            require_once BASE_PATH . 'app/Router.php';
        }
        Router::view($viewPath, $data);
    }
}
