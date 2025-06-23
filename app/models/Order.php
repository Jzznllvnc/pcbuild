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
     * @param int|null $userId The ID of the user, or null for guest orders.
     * @param float $totalAmount The total amount of the order.
     * @param string $paymentMethod The selected payment method.
     * @param array $cartItems An array of associative arrays, each representing a cart item.
     * @return int|false The ID of the newly created order, or false on failure.
     */
    public function createOrder($userId, $totalAmount, $paymentMethod, array $cartItems)
    {
        try {
            // Start a transaction to ensure atomicity
            $this->pdo->beginTransaction();

            // 1. Insert into orders table
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (:user_id, :total_amount, :payment_method)");
            $stmt->execute([
                ':user_id' => $userId,
                ':total_amount' => $totalAmount,
                ':payment_method' => $paymentMethod
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
     * Fetches an order by its ID, including its items.
     * @param int $orderId The ID of the order to fetch.
     * @return array|false The order details with nested items, or false if not found.
     */
    public function getOrderById($orderId)
    {
        try {
            // Fetch order details
            $stmtOrder = $this->pdo->prepare("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = :order_id LIMIT 1");
            $stmtOrder->execute([':order_id' => $orderId]);
            $order = $stmtOrder->fetch();

            if (!$order) {
                return false;
            }

            // Fetch order items
            $stmtItems = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
            $stmtItems->execute([':order_id' => $orderId]);
            $order['items'] = $stmtItems->fetchAll();

            return $order;

        } catch (PDOException $e) {
            error_log("Order Model Error: getOrderById failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetches all orders for a specific user ID, including their items.
     * @param int $userId The ID of the user.
     * @return array An array of orders, each with its items.
     */
    public function getOrdersByUserId($userId)
    {
        try {
            // Fetch all orders for the user
            $stmtOrders = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
            $stmtOrders->execute([':user_id' => $userId]);
            $orders = $stmtOrders->fetchAll();

            // For each order, fetch its items
            foreach ($orders as &$order) { // Use & to modify array by reference
                $stmtItems = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
                $stmtItems->execute([':order_id' => $order['id']]);
                $order['items'] = $stmtItems->fetchAll();
            }

            return $orders;

        } catch (PDOException $e) {
            error_log("Order Model Error: getOrdersByUserId failed: " . $e->getMessage());
            return [];
        }
    }
}
