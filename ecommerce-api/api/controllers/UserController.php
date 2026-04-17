<?php
require_once __DIR__ . '/../inc/response.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $user;
    
    public function __construct($db) {
        $this->user = new User($db);
    }
    
    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!isset($data['full_name']) || !isset($data['email']) || !isset($data['password'])) {
            sendResponse("error", "Missing required fields: full_name, email, password", null, 400);
        }
        
        $this->user->full_name = $data['full_name'];
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        $this->user->phone = $data['phone'] ?? '';
        $this->user->address = $data['address'] ?? '';
        
        if($this->user->register()) {
            sendResponse("success", "User registered successfully", null, 201);
        } else {
            sendResponse("error", "Email already exists or registration failed", null, 400);
        }
    }
    
    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!isset($data['email']) || !isset($data['password'])) {
            sendResponse("error", "Email and password required", null, 400);
        }
        
        $this->user->email = $data['email'];
        $this->user->password = $data['password'];
        
        $result = $this->user->login();
        
        if($result) {
            sendResponse("success", "Login successful", $result);
        } else {
            sendResponse("error", "Invalid email or password", null, 401);
        }
    }
    
    public function getProfile($user_id) {
        $result = $this->user->getById($user_id);
        if($result) {
            sendResponse("success", "User found", $result);
        } else {
            sendResponse("error", "User not found", null, 404);
        }
    }
}
?>