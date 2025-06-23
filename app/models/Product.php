<?php

class Product
{
    private $pdo;
    private $table_name = "products";

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get a single product by its ID.
     *
     * @param int $id The product ID.
     * @return array|false The product data as an associative array, or false if not found.
     */
    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all products with optional pagination, category, and search filters.
     *
     * @param int $limit Number of products to return.
     * @param int $offset Offset for pagination.
     * @param string|null $category Optional category to filter by.
     * @param string|null $search Optional search term for name or description.
     * @return array A list of product data as associative arrays.
     */
    public function getProducts($limit, $offset, $category = null, $search = null)
    {
        $query = "SELECT * FROM " . $this->table_name;
        $params = [];
        $whereClauses = [];

        // Build parameters first
        if (!empty($category)) {
            $params[':category'] = $category;
            $whereClauses[] = "category = :category";
        }

        if (!empty($search)) {
            // Use distinct parameters for multiple LIKE conditions
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_description'] = '%' . $search . '%';
            $whereClauses[] = "(name LIKE :search_name OR description LIKE :search_description)";
        }

        // Only append WHERE clause if conditions exist
        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($query);

        // Merge all parameters (including limit and offset) for execution
        $executeParams = $params;
        $executeParams[':limit'] = $limit;
        $executeParams[':offset'] = $offset;

        $stmt->execute($executeParams);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get products filtered by a specific category.
     * This is a new method added to support the BuildController.
     *
     * @param string $category The category to filter products by.
     * @return array A list of product data as associative arrays.
     */
    public function getProductsByCategory($category)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE category = :category ORDER BY name ASC");
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total number of products with optional category and search filters.
     *
     * @param string|null $category Optional category to filter by.
     * @param string|null $search Optional search term for name or description.
     * @return int The total number of products.
     */
    public function getTotalProducts($category = null, $search = null)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name;
        $params = [];
        $whereClauses = [];

        // Build parameters first
        if (!empty($category)) {
            $params[':category'] = $category;
            $whereClauses[] = "category = :category";
        }

        if (!empty($search)) {
            // Use distinct parameters for multiple LIKE conditions
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_description'] = '%' . $search . '%';
            $whereClauses[] = "(name LIKE :search_name OR description LIKE :search_description)";
        }

        // Only append WHERE clause if conditions exist
        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->pdo->prepare($query);
        // Execute with parameters. If $params is empty, it's equivalent to execute().
        $stmt->execute($params); 
        
        return (int)$stmt->fetchColumn();
    }
}
