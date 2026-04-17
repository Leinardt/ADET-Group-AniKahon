<?php
class Product {
    private $conn;
    private $table = "products";
    
    public $product_id;
    public $category_id;
    public $product_name;
    public $price;
    public $stock_quantity;
    public $description;
    public $image_url;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT p.*, c.category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT p.*, c.category_name 
                  FROM " . $this->table . " p
                  LEFT JOIN categories c ON p.category_id = c.category_id
                  WHERE p.product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByCategory($category_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (category_id, product_name, price, stock_quantity, description, image_url) 
                  VALUES (:category_id, :product_name, :price, :stock_quantity, :description, :image_url)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":product_name", $this->product_name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image_url", $this->image_url);
        
        return $stmt->execute();
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET category_id = :category_id, 
                      product_name = :product_name, 
                      price = :price, 
                      stock_quantity = :stock_quantity, 
                      description = :description, 
                      image_url = :image_url 
                  WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":product_name", $this->product_name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":product_id", $this->product_id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE product_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    
    public function updateStock($id, $quantity) {
        $query = "UPDATE " . $this->table . " SET stock_quantity = stock_quantity - :quantity 
                  WHERE product_id = :id AND stock_quantity >= :quantity";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>