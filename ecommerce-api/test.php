<?php
header("Content-Type: application/json");
echo json_encode([
    "status" => "success",
    "message" => "Simple API test is working",
    "products" => [
        ["id" => 1, "name" => "Test Necklace", "price" => 100],
        ["id" => 2, "name" => "Test Bracelet", "price" => 80]
    ]
]);
?>