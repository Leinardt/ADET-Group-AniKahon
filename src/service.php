<?php
header("Content-Type: application/json");

// GET JSON INPUT
$data = json_decode(file_get_contents("php://input"), true);

// GET 2 PARAMETERS 
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// SAMPLE USERS 
$users = [
    ["username" => "nyanjeanqalcovindas", "password" => "njqa14151617"],
    ["username" => "lancechristophertdelosreyes", "password" => "lctd67676767"],
    ["username" => "leinardtromadto", "password" => "lro12345678"],
    ["username" => "rhonamaerpancho", "password" => "rmrp910111213"]
];

// CHECK IF USERNAME EXISTS
$userFound = false;

foreach ($users as $user) {
    if ($user['username'] === $username) {
        $userFound = true;

        // CHECK PASSWORD
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

// IF USERNAME NOT FOUND
echo json_encode([
    "status" => "error",
    "message" => "Invalid username"
]);
?>
