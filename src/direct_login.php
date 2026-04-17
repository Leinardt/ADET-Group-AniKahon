<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "ERROR", "message" => "USE POST METHOD"]);
    exit();
}

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$input = json_decode(file_get_contents("php://input"), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status" => "ERROR",
        "message" => "BOTH USERNAME AND PASSWORD ARE REQUIRED"
    ]);
    exit();
}

$query = "SELECT user_id, full_name, email, password, role FROM users 
          WHERE email = :login OR full_name = :login";
$stmt = $db->prepare($query);
$stmt->bindParam(":login", $username);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && $password === $user['password']) {
    unset($user['password']);
    echo json_encode([
        "status" => "SUCCESS",
        "message" => "LOGIN SUCCESSFUL",
        "data" => [
            "user_id" => $user['user_id'],
            "name" => $user['full_name'],
            "email" => $user['email'],
            "role" => $user['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "ERROR",
        "message" => "INVALID USERNAME OR PASSWORD"
    ]);
}
?>
