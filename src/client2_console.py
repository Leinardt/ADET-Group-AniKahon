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

// DB Connection
$host     = "localhost";
$db_name  = "adet_db";
$db_user  = "root";
$db_pass  = "";

try {
    $db = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status"  => "ERROR",
        "message" => "Database connection failed: " . $e->getMessage()
    ]);
    exit();
}

// Input
$input    = json_decode(file_get_contents("php://input"), true);
$username = trim($input['username'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        "status"  => "ERROR",
        "message" => "BOTH USERNAME AND PASSWORD ARE REQUIRED"
    ]);
    exit();
}

// Query
$query = "SELECT user_id, full_name, username, password, role FROM users 
          WHERE username = :login OR full_name = :login";
$stmt  = $db->prepare($query);
$stmt->bindParam(":login", $username);
$stmt->execute();

$user = $stmt->fetch();

if ($user && $password === $user['password']) {
    echo json_encode([
        "status"  => "SUCCESS",
        "message" => "LOGIN SUCCESSFUL",
        "data"    => [
            "user_id"  => $user['user_id'],
            "name"     => $user['full_name'],
            "username" => $user['username'],
            "role"     => $user['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status"  => "ERROR",
        "message" => "INVALID USERNAME OR PASSWORD"
    ]);
}
?>
