<?php
class Cart {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getCart($user_id) {
        $query = "SELECT c.cart_id, ci.cart_item_id, ci.product_id, ci.quantity, 
                         p.product_name, p.price, (ci.quantity * p.price) as subtotal
                  FROM cart c
                  JOIN cart_items ci ON c.cart_id = ci.cart_id
                  JOIN products p ON ci.product_id = p.product_id
                  WHERE c.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addToCart($user_id, $product_id, $quantity) {
        // Get cart_id for user
        $cart_query = "SELECT cart_id FROM cart WHERE user_id = :user_id";
        $cart_stmt = $this->conn->prepare($cart_query);
        $cart_stmt->bindParam(":user_id", $user_id);
        $cart_stmt->execute();
        $cart = $cart_stmt->fetch(PDO::FETCH_ASSOC);
        $cart_id = $cart['cart_id'];
        
        // Check if product already in cart
        $check_query = "SELECT cart_item_id, quantity FROM cart_items 
                        WHERE cart_id = :cart_id AND product_id = :product_id";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":cart_id", $cart_id);
        $check_stmt->bindParam(":product_id", $product_id);
        $check_stmt->execute();
        
        if($check_stmt->rowCount() > 0) {
            // Update quantity
            $item = $check_stmt->fetch(PDO::FETCH_ASSOC);
            $new_quantity = $item['quantity'] + $quantity;
            $update_query = "UPDATE cart_items SET quantity = :quantity 
                             WHERE cart_item_id = :cart_item_id";
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bindParam(":quantity", $new_quantity);
            $update_stmt->bindParam(":cart_item_id", $item['cart_item_id']);
            return $update_stmt->execute();
        } else {
            // Add new item
            $insert_query = "INSERT INTO cart_items (cart_id, product_id, quantity) 
                             VALUES (:cart_id, :product_id, :quantity)";
            $insert_stmt = $this->conn->prepare($insert_query);
            $insert_stmt->bindParam(":cart_id", $cart_id);
            $insert_stmt->bindParam(":product_id", $product_id);
            $insert_stmt->bindParam(":quantity", $quantity);
            return $insert_stmt->execute();
        }
    }
    
    public function removeFromCart($user_id, $cart_item_id) {
        $query = "DELETE ci FROM cart_items ci
                  JOIN cart c ON ci.cart_id = c.cart_id
                  WHERE ci.cart_item_id = :cart_item_id AND c.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cart_item_id", $cart_item_id);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }
    
    public function clearCart($user_id) {
        $query = "DELETE ci FROM cart_items ci
                  JOIN cart c ON ci.cart_id = c.cart_id
                  WHERE c.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        return $stmt->execute();
    }
}
?>