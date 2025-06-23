<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';
require_once BASE_PATH . 'app/models/Product.php';

class ProductController extends BaseController
{
    protected $productModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->productModel = new Product($pdo);
    }

    public function index()
    {
        // Pagination setup
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $productsPerPage = 6; // Number of products to display per page
        $offset = ($page - 1) * $productsPerPage;

        $category = $_GET['category'] ?? null;
        $search = $_GET['search'] ?? null;

        // Fetch products with pagination, category, and search filters
        $products = $this->productModel->getProducts($productsPerPage, $offset, $category, $search);
        
        // Get total number of products for pagination
        $totalProducts = $this->productModel->getTotalProducts($category, $search);
        $totalPages = ceil($totalProducts / $productsPerPage);

        $data = [
            'title' => 'Our Product Catalog',
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $category,
            'currentSearch' => $search
        ];

        $this->view('products/index', $data);
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            // Handle product not found, e.g., redirect to 404 or product list
            header('Location: /pcbuild/public/products');
            exit();
        }

        $data = [
            'title' => $product['name'],
            'product' => $product
        ];

        $this->view('products/show', $data);
    }
}
