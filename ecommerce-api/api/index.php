<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/inc/response.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/OrderController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-User-Id");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$request_uri = $_SERVER['REQUEST_URI'];

$request_uri = strtok($request_uri, '?');

$base_path = '/ecommerce-api/api/';
if (strpos($request_uri, $base_path) === 0) {
    $path = substr($request_uri, strlen($base_path));
} else {
    if (preg_match('/\/ecommerce-api\/api\/(.*)/', $request_uri, $matches)) {
        $path = $matches[1];
    } else {
        $path = '';
    }
}

$path = trim($path, '/');

$parts = explode('/', $path);
$resource = $parts[0] ?: '';
$id = isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : null;
$sub_resource = isset($parts[1]) && !is_numeric($parts[1]) ? $parts[1] : null;

$method = $_SERVER['REQUEST_METHOD'];

$headers = getallheaders();
$user_id = isset($headers['X-User-Id']) ? $headers['X-User-Id'] : null;

// ========== ROUTING ==========

try {
    // PRODUCTS ROUTE
    if ($resource === 'products') {
        $controller = new ProductController($db);
        
        if ($method === 'GET') {
            if ($id) {
                $controller->getById($id);
            } else if (isset($_GET['category_id'])) {
                $controller->getByCategory($_GET['category_id']);
            } else {
                $controller->getAll();
            }
        } 
        else if ($method === 'POST') {
            $controller->create();
        }
        else if ($method === 'PUT' && $id) {
            $controller->update($id);
        }
        else if ($method === 'DELETE' && $id) {
            $controller->delete($id);
        }
        else {
            sendResponse("error", "Method not allowed for products", null, 405);
        }
    }
    
    // CATEGORIES ROUTE
    else if ($resource === 'categories') {
        if ($method === 'GET') {
            $stmt = $db->query("SELECT * FROM categories ORDER BY category_name");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            sendResponse("success", "Categories retrieved", $categories);
        } else {
            sendResponse("error", "Method not allowed", null, 405);
        }
    }
    
    // ========== NEW: SIMPLE LOGIN ROUTE (for GUI client) ==========
    // This accepts username + password (2 parameters) and returns JSON
    else if ($resource === 'login') {
        // Only accept POST requests
        if ($method !== 'POST') {
            sendResponse("error", "Method not allowed. Use POST", null, 405);
        }
        
        // Get JSON input from request body
        $input = json_decode(file_get_contents("php://input"), true);
        
        // Get the 2 required parameters: username and password
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';
        
        // Validate both parameters are present
        if (empty($username) || empty($password)) {
            sendResponse("error", "Both username and password are required", [
                "required_params" => ["username", "password"]
            ], 400);
        }
        
        // Search for user by email OR full_name
        $query = "SELECT user_id, full_name, email, password, role FROM users 
                  WHERE email = :login OR full_name = :login";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":login", $username);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Login successful - remove password from response
            unset($user['password']);
            
            sendResponse("success", "Login successful", [
                "user_id" => $user['user_id'],
                "name" => $user['full_name'],
                "email" => $user['email'],
                "role" => $user['role']
            ]);
        } else {
            sendResponse("error", "Invalid username or password", null, 401);
        }
    }
    
    // AUTH ROUTES (original - keeps working)
    else if ($resource === 'auth') {
        $controller = new UserController($db);
        
        if ($sub_resource === 'register') {
            $controller->register();
        } else if ($sub_resource === 'login') {
            $controller->login();
        } else {
            sendResponse("error", "Invalid auth endpoint. Use /auth/register or /auth/login", null, 404);
        }
    }
    
    // USER PROFILE ROUTE
    else if ($resource === 'users' && $id) {
        $controller = new UserController($db);
        $controller->getProfile($id);
    }
    
    // CART ROUTES
    else if ($resource === 'cart') {
        if (!$user_id) {
            sendResponse("error", "X-User-Id header is required", null, 401);
        }
        
        $controller = new CartController($db);
        
        if ($method === 'GET') {
            $controller->getCart($user_id);
        } 
        else if ($method === 'POST') {
            $controller->addToCart($user_id);
        }
        else if ($method === 'DELETE') {
            if ($id) {
                $controller->removeFromCart($user_id, $id);
            } else {
                $controller->clearCart($user_id);
            }
        }
        else {
            sendResponse("error", "Method not allowed for cart", null, 405);
        }
    }
    
    // ORDERS ROUTES
    else if ($resource === 'orders') {
        if (!$user_id) {
            sendResponse("error", "X-User-Id header is required", null, 401);
        }
        
        $controller = new OrderController($db);
        
        if ($method === 'GET') {
            if ($id) {
                $controller->getOrderDetails($user_id, $id);
            } else {
                $controller->getUserOrders($user_id);
            }
        }
        else if ($method === 'POST') {
            $controller->createOrder($user_id);
        }
        else {
            sendResponse("error", "Method not allowed for orders", null, 405);
        }
    }
    
    // ROOT ENDPOINT
    else if ($resource === '') {
        sendResponse("success", "AniKahon API is running", [
            "api_name" => "AniKahon",
            "version" => "1.0.0",
            "endpoints" => [
                "GET /products" => "Get all products",
                "GET /products/{id}" => "Get product by ID", 
                "GET /products?category_id={id}" => "Get products by category",
                "GET /categories" => "Get all categories",
                "POST /login" => "🔐 LOGIN (username + password) - For GUI Client",
                "POST /auth/register" => "Register new user",
                "POST /auth/login" => "Login user (original)",
                "GET /users/{id}" => "Get user profile",
                "GET /cart" => "View cart (X-User-Id header)",
                "POST /cart" => "Add to cart (X-User-Id header)",
                "DELETE /cart" => "Clear cart (X-User-Id header)",
                "DELETE /cart/{id}" => "Remove item (X-User-Id header)",
                "GET /orders" => "Get orders (X-User-Id header)",
                "POST /orders" => "Create order (X-User-Id header)",
                "GET /orders/{id}" => "Get order details (X-User-Id header)"
            ]
        ]);
    }
    
    // 404 - NOT FOUND
    else {
        sendResponse("error", "Endpoint '{$resource}' not found", [
            "available_endpoints" => ["products", "categories", "login", "auth", "users", "cart", "orders"]
        ], 404);
    }
    
} catch (Exception $e) {
    sendResponse("error", "Server error: " . $e->getMessage(), null, 500);
}
?>