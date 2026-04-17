<?php
class Order {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function createOrder($user_id, $shipping_address, $payment_method) {
        try {
            $this->conn->beginTransaction();
            
            // Get cart items
            $cart_query = "SELECT ci.product_id, ci.quantity, p.price 
                           FROM cart c
                           JOIN cart_items ci ON c.cart_id = ci.cart_id
                           JOIN products p ON ci.product_id = p.product_id
                           WHERE c.user_id = :user_id";
            $cart_stmt = $this->conn->prepare($cart_query);
            $cart_stmt->bindParam(":user_id", $user_id);
            $cart_stmt->execute();
            $cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if(empty($cart_items)) {
                return ["error" => "Cart is empty"];
            }
            
            // Calculate total
            $total = 0;
            foreach($cart_items as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            
            // Create order
            $order_query = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, status) 
                            VALUES (:user_id, :total, :shipping_address, :payment_method, 'pending')";
            $order_stmt = $this->conn->prepare($order_query);
            $order_stmt->bindParam(":user_id", $user_id);
            $order_stmt->bindParam(":total", $total);
            $order_stmt->bindParam(":shipping_address", $shipping_address);
            $order_stmt->bindParam(":payment_method", $payment_method);
            $order_stmt->execute();
            
            $order_id = $this->conn->lastInsertId();
            
            // Add order items and update stock
            foreach($cart_items as $item) {
                $item_query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_time) 
                               VALUES (:order_id, :product_id, :quantity, :price)";
                $item_stmt = $this->conn->prepare($item_query);
                $item_stmt->bindParam(":order_id", $order_id);
                $item_stmt->bindParam(":product_id", $item['product_id']);
                $item_stmt->bindParam(":quantity", $item['quantity']);
                $item_stmt->bindParam(":price", $item['price']);
                $item_stmt->execute();
                
                // Update stock
                $stock_query = "UPDATE products SET stock_quantity = stock_quantity - :quantity 
                                WHERE product_id = :product_id";
                $stock_stmt = $this->conn->prepare($stock_query);
                $stock_stmt->bindParam(":quantity", $item['quantity']);
                $stock_stmt->bindParam(":product_id", $item['product_id']);
                $stock_stmt->execute();
            }
            
            // Clear cart
            $clear_query = "DELETE ci FROM cart_items ci
                            JOIN cart c ON ci.cart_id = c.cart_id
                            WHERE c.user_id = :user_id";
            $clear_stmt = $this->conn->prepare($clear_query);
            $clear_stmt->bindParam(":user_id", $user_id);
            $clear_stmt->execute();
            
            $this->conn->commit();
            return ["order_id" => $order_id, "total" => $total];
            
        } catch(Exception $e) {
            $this->conn->rollBack();
            return ["error" => $e->getMessage()];
        }
    }
    
    public function getUserOrders($user_id) {
        $query = "SELECT order_id, order_date, total_amount, status, shipping_address 
                  FROM orders WHERE user_id = :user_id 
                  ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOrderDetails($order_id, $user_id) {
        $query = "SELECT o.*, oi.product_id, oi.quantity, oi.price_at_time, p.product_name
                  FROM orders o
                  JOIN order_items oi ON o.order_id = oi.order_id
                  JOIN products p ON oi.product_id = p.product_id
                  WHERE o.order_id = :order_id AND o.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>