<?php
class User {
    private $conn;
    private $table = "users";
    
    public $user_id;
    public $full_name;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $role;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function register() {
        $query = "INSERT INTO " . $this->table . " 
                  (full_name, email, password, phone, address) 
                  VALUES (:full_name, :email, :password, :phone, :address)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":address", $this->address);
        
        if($stmt->execute()) {
            $user_id = $this->conn->lastInsertId();
            
            // Create cart for user (User → Cart 1:1)
            $cart_query = "INSERT INTO cart (user_id) VALUES (:user_id)";
            $cart_stmt = $this->conn->prepare($cart_query);
            $cart_stmt->bindParam(":user_id", $user_id);
            $cart_stmt->execute();
            
            return true;
        }
        return false;
    }
    
    public function login() {
        $query = "SELECT user_id, full_name, email, password, role FROM " . $this->table . " 
                  WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && password_verify($this->password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function getById($id) {
        $query = "SELECT user_id, full_name, email, phone, address, role, created_at 
                  FROM " . $this->table . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>