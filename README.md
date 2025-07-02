# PC Build E-commerce Application

This is a web-based e-commerce application designed to help users build custom PCs, browse components, manage their orders, and interact with an AI assistant. The application includes both user-facing functionalities and an administrative panel for managing products and users.

## Features

* **User Authentication**: Secure user registration, login, password recovery (forgot/reset password).
* **Product Catalog**: Browse and view detailed information about various PC components.
* **Custom PC Builder**: A dedicated section to help users select components and build their custom PC.
* **Shopping Cart**: Add products to a cart and manage quantities.
* **Checkout Process**: Seamless checkout flow for completing purchases.
* **Order History**: Users can view their past orders.
* **AI Chat Assistant**: An integrated AI chat feature to assist users with their queries (e.g., component compatibility, build advice).
* **Admin Panel**:
    * Dashboard for an overview of the application.
    * User Management: View and manage registered users and their orders.
    * Product Management: Create, edit, and delete products from the catalog.
* **Image Uploads**: Functionality for handling product image uploads.

## Technologies Used

* **Backend**: PHP
* **Frontend**: HTML, CSS, JavaScript (specifically `assets/js/main.js` for client-side interactions)
* **Database**: MySQL (configured via `config/database.php`)
* **Routing**: Custom routing implementation (`app/Router.php`)

## Setup and Installation

To get this project up and running on your local machine, follow these steps:

1.  **Clone the repository**:
    ```bash
    git clone <your-repository-url>
    cd <your-project-directory>
    ```

2.  **Web Server**: Ensure you have a web server (like Apache or Nginx) and PHP installed and configured.

3.  **Database Setup**:
    * Create a MySQL database for the application.
    * Update the database connection details in `config/database.php` with your database credentials (username, password, database name).
        ```php
        // Example structure in config/database.php
        <?php
        define('DB_HOST', 'localhost');
        define('DB_USER', 'your_db_username');
        define('DB_PASS', 'your_db_password');
        define('DB_NAME', 'your_db_name');
        ```
    * Import your database schema (e.g., `schema.sql` if you have one, or create tables manually based on your models like `User`, `Product`, `Order`, etc.).

4.  **Install PHP Dependencies (if applicable)**:
    If your project uses Composer for dependency management (e.g., if there's a `composer.json` file and `vendor/` directory), run:
    ```bash
    composer install
    ```

5.  **Configure API Keys**:
    * The `app/controllers/AiChatController.php` contains your Gemini API key. For production or shared environments, it's highly recommended to use environment variables (e.g., through a `.env` file) instead of hardcoding the key directly in the file.
    * Ensure your `.gitignore` file excludes `AiChatController.php` and any `.env` files if you implement them.

6.  **Access the Application**:
    * Point your web server's document root to the project's public directory (e.g., if `index.php` is in the root, point to that directory).
    * Open your web browser and navigate to the application's URL (e.g., `http://localhost/`).

## Usage

* **Register a new account** or **log in** if you have existing credentials.
* **Browse products** from the main navigation.
* **Add products to your cart** and proceed to checkout.
* **Use the PC Builder** to assemble a custom computer.
* **Interact with the AI Chat** for assistance.
* **Admin Access**: If you have administrator privileges, navigate to the `/admin` route (e.g., `http://localhost/admin`) to manage users and products.

## Contributing

Contributions are welcome! Please feel free to fork the repository, make your changes, and submit a pull request.

## License

This project is open-source and available under the [MIT License](LICENSE) (or specify your chosen license).