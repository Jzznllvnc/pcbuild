<?php

class Order
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Creates a new order in the database.
     *
     * @param array $orderDetails Associative array containing all order details.
     * Expected keys: user_id, total_amount, payment_method, shipping_method, shipping_cost,
     * first_name, last_name, email, country_code, shipping_mobile_number, address, city, state, zip_code, notes, payment_mobile_number.
     * @return int|false The ID of the newly created order, or false on failure.
     */
    public function createOrder($orderDetails) // Modified to accept a single array
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO orders (user_id, total_amount, payment_method, shipping_method, shipping_cost, first_name, last_name, email, country_code, shipping_mobile_number, address, city, state, zip_code, notes, payment_mobile_number) VALUES (:user_id, :total_amount, :payment_method, :shipping_method, :shipping_cost, :first_name, :last_name, :email, :country_code, :shipping_mobile_number, :address, :city, :state, :zip_code, :notes, :payment_mobile_number)");

            $stmt->execute([
                ':user_id' => $orderDetails['user_id'],
                ':total_amount' => $orderDetails['total_amount'],
                ':payment_method' => $orderDetails['payment_method'],
                ':shipping_method' => $orderDetails['shipping_info']['shipping_method'], // Access nested array
                ':shipping_cost' => $orderDetails['shipping_info']['shipping_cost'],   // Access nested array
                ':first_name' => $orderDetails['shipping_info']['first_name'],
                ':last_name' => $orderDetails['shipping_info']['last_name'],
                ':email' => $orderDetails['shipping_info']['email'],
                ':country_code' => $orderDetails['shipping_info']['country_code'],
                ':shipping_mobile_number' => $orderDetails['shipping_info']['mobile_number'],
                ':address' => $orderDetails['shipping_info']['address'],
                ':city' => $orderDetails['shipping_info']['city'],
                ':state' => $orderDetails['shipping_info']['state'],
                ':zip_code' => $orderDetails['shipping_info']['zip_code'],
                ':notes' => $orderDetails['shipping_info']['notes'],
                ':payment_mobile_number' => $orderDetails['shipping_info']['payment_mobile_number']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Order Model Error: createOrder failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Adds items to a specific order.
     *
     * @param int $orderId
     * @param array $items An array of arrays, each with product_id, product_name, quantity, price_at_purchase.
     * @return bool True on success, false on failure.
     */
    public function addOrderItems($orderId, $items)
    {
        try {
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price_at_purchase) VALUES (:order_id, :product_id, :product_name, :quantity, :price_at_purchase)");

            foreach ($items as $item) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['id'], // Use 'id' from the cart item structure
                    ':product_name' => $item['name'], // Use 'name' from the cart item structure
                    ':quantity' => $item['quantity'],
                    ':price_at_purchase' => $item['price'] // Use 'price' from the cart item structure
                ]);
            }
            return $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Order Model Error: addOrderItems failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all orders for a given user ID, including their items.
     *
     * @param int $userId The ID of the user.
     * @return array An array of orders, with each order containing its items.
     */
    public function getOrdersByUserId($userId)
    {
        $orders = [];
        try {
            // Fetch orders for the user
            $stmtOrders = $this->pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_date DESC");
            $stmtOrders->execute([':user_id' => $userId]);
            $rawOrders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawOrders as $order) {
                // For each order, fetch its items
                $order['items'] = $this->getOrderItemsByOrderId($order['id']);
                $orders[] = $order;
            }
        } catch (PDOException $e) {
            error_log("Order Model Error: getOrdersByUserId failed: " . $e->getMessage());
        }
        return $orders;
    }

    /**
     * Retrieves all items for a given order ID.
     *
     * @param int $orderId The ID of the order.
     * @return array An array of order items.
     */
    public function getOrderItemsByOrderId($orderId)
    {
        $items = [];
        try {
            $stmtItems = $this->pdo->prepare("SELECT oi.*, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id");
            $stmtItems->execute([':order_id' => $orderId]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Order Model Error: getOrderItemsByOrderId failed: " . $e->getMessage());
        }
        return $items;
    }

    /**
     * Retrieves a single order by its ID, including its items.
     *
     * @param int $orderId The ID of the order to retrieve.
     * @return array|false The order data as an associative array, including its items, or false if not found.
     */
    public function getOrderById($orderId)
    {
        try {
            $stmtOrder = $this->pdo->prepare("SELECT * FROM orders WHERE id = :order_id LIMIT 1");
            $stmtOrder->execute([':order_id' => $orderId]);
            $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                $order['items'] = $this->getOrderItemsByOrderId($orderId);
            }
            return $order;
        } catch (PDOException $e) {
            error_log("Order Model Error: getOrderById failed: " . $e->getMessage());
            return false;
        }
    }
}