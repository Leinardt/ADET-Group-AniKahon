<?php
header("Content-Type: application/json");

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Get parameters (at least 2 required)
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Sample users (no database needed)
$users = [
    ["username" => "admin", "password" => "1234"],
    ["username" => "user", "password" => "pass"]
];

// Check if username exists
$userFound = false;

foreach ($users as $user) {
    if ($user['username'] === $username) {
        $userFound = true;

        // Check password
        if ($user['password'] === $password) {
            echo json_encode([
                "status" => "success",
                "message" => "Login successful"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid password"
            ]);
        }
        exit;
    }
}

// If username not found
echo json_encode([
    "status" => "error",
    "message" => "Invalid username"
]);
?>