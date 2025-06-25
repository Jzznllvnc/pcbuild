<?php

class Order
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Saves a new order and its items to the database.
     * Now accepts a structured $orderDetails array.
     *
     * @param array $orderDetails An associative array containing:
     * - 'user_id': int|null The ID of the user, or null for guest orders.
     * - 'total_amount': float The total amount of the order.
     * - 'payment_method': string The selected payment method.
     * - 'cart_items': array An array of associative arrays, each representing a cart item.
     * - 'shipping_info': array An array of shipping details including:
     * - 'first_name', 'last_name', 'email', 'country_code', 'mobile_number',
     * - 'address', 'city', 'state', 'zip_code', 'notes',
     * - 'shipping_method', 'shipping_cost', 'payment_mobile_number'
     *
     * @return int|false The ID of the newly created order, or false on failure.
     */
    public function createOrder(array $orderDetails)
    {
        try {
            // Start a transaction to ensure atomicity
            $this->pdo->beginTransaction();

            // Destructure order details for easier access
            $userId = $orderDetails['user_id'] ?? null;
            $totalAmount = $orderDetails['total_amount'];
            $paymentMethod = $orderDetails['payment_method'];
            $cartItems = $orderDetails['cart_items'];
            $shippingInfo = $orderDetails['shipping_info'];

            // 1. Insert into orders table
            // IMPORTANT: You MUST add these columns to your 'orders' table in your database
            // (e.g., first_name, last_name, email, country_code, shipping_mobile_number, address,
            // city, state, zip_code, notes, shipping_method, shipping_cost, payment_mobile_number)
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (
                    user_id, total_amount, payment_method,
                    first_name, last_name, email, country_code, shipping_mobile_number,
                    address, city, state, zip_code, notes,
                    shipping_method, shipping_cost, payment_mobile_number
                ) VALUES (
                    :user_id, :total_amount, :payment_method,
                    :first_name, :last_name, :email, :country_code, :shipping_mobile_number,
                    :address, :city, :state, :zip_code, :notes,
                    :shipping_method, :shipping_cost, :payment_mobile_number
                )
            ");

            $stmt->execute([
                ':user_id' => $userId,
                ':total_amount' => $totalAmount,
                ':payment_method' => $paymentMethod,
                ':first_name' => $shippingInfo['first_name'],
                ':last_name' => $shippingInfo['last_name'],
                ':email' => $shippingInfo['email'],
                ':country_code' => $shippingInfo['country_code'],
                ':shipping_mobile_number' => $shippingInfo['mobile_number'], // Store shipping-specific mobile number
                ':address' => $shippingInfo['address'],
                ':city' => $shippingInfo['city'],
                ':state' => $shippingInfo['state'],
                ':zip_code' => $shippingInfo['zip_code'],
                ':notes' => $shippingInfo['notes'],
                ':shipping_method' => $shippingInfo['shipping_method'],
                ':shipping_cost' => $shippingInfo['shipping_cost'],
                ':payment_mobile_number' => $shippingInfo['payment_mobile_number'] // Store payment-specific mobile number
            ]);
            $orderId = $this->pdo->lastInsertId();

            if (!$orderId) {
                throw new Exception("Failed to create order in 'orders' table.");
            }

            // 2. Insert into order_items table for each cart item
            $stmtItems = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price_at_purchase) VALUES (:order_id, :product_id, :product_name, :quantity, :price_at_purchase)");

            foreach ($cartItems as $item) {
                $stmtItems->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'],
                    ':product_name' => $item['name'],
                    ':quantity' => $item['quantity'],
                    ':price_at_purchase' => $item['price']
                ]);
            }

            // Commit the transaction
            $this->pdo->commit();

            return $orderId;

        } catch (Exception $e) {
            // Rollback on error
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Order Model Error: createOrder failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches an order by its ID, including its items and shipping details.
     * NOW INCLUDES product_image_url for each item.
     * @param int $orderId The ID of the order to fetch.
     * @return array|false The order details with nested items, or false if not found.
     */
    public function getOrderById($orderId)
    {
        try {
            // Fetch order details, including new shipping columns
            $stmtOrder = $this->pdo->prepare("
                SELECT o.*, u.username 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = :order_id LIMIT 1
            "); // Assuming 'shipping_info' columns are in 'orders' table
            $stmtOrder->execute([':order_id' => $orderId]);
            $order = $stmtOrder->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

            if (!$order) {
                return false;
            }

            // Fetch order items, now joining with products to get image_url
            $stmtItems = $this->pdo->prepare("
                SELECT oi.*, p.image_url 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = :order_id
            ");
            $stmtItems->execute([':order_id' => $orderId]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

            return $order;

        } catch (PDOException $e) {
            error_log("Order Model Error: getOrderById failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches all orders for a specific user ID, including their items.
     * NOW INCLUDES product_image_url for each item.
     * @param int $userId The ID of the user.
     * @return array An array of orders, each with its items.
     */
    public function getOrdersByUserId($userId)
    {
        try {
            // Fetch all orders for the user
            $stmtOrders = $this->pdo->prepare("
                SELECT * FROM orders 
                WHERE user_id = :user_id 
                ORDER BY order_date DESC
            ");
            $stmtOrders->execute([':user_id' => $userId]);
            $orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

            // For each order, fetch its items, joining with products to get image_url
            foreach ($orders as &$order) { // Use & to modify array by reference
                $stmtItems = $this->pdo->prepare("
                    SELECT oi.*, p.image_url 
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = :order_id
                ");
                $stmtItems->execute([':order_id' => $order['id']]);
                $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array
            }

            return $orders;

        } catch (PDOException $e) {
            error_log("Order Model Error: getOrdersByUserId failed: " . $e->getMessage());
            return [];
        }
    }
}
