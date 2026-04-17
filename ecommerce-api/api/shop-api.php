<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['route']) ? $_GET['route'] : '';

if ($method === 'GET' && $path === 'products') {
    // Get all products
    $stmt = $db->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $products]);
}
else if ($method === 'GET' && preg_match('/products\/(\d+)/', $path, $matches)) {
    // Get single product
    $id = $matches[1];
    $stmt = $db->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $product]);
}
else if ($method === 'GET' && $path === 'categories') {
    // Get categories
    $stmt = $db->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $categories]);
}
else if ($method === 'GET' && $path === '') {
    // API info
    echo json_encode([
        "status" => "success",
        "message" => "AniKahon API is running",
        "how_to_use" => [
            "all_products" => "/api/shop-api.php?route=products",
            "single_product" => "/api/shop-api.php?route=products/1",
            "categories" => "/api/shop-api.php?route=categories"
        ]
    ]);
}
else {
    echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
}
?>