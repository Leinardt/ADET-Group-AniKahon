<?php
require_once __DIR__ . '/../inc/response.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $product;
    
    public function __construct($db) {
        $this->product = new Product($db);
    }
    
    public function getAll() {
        $products = $this->product->getAll();
        sendResponse("success", "Products retrieved", $products);
    }
    
    public function getById($id) {
        $product = $this->product->getById($id);
        if($product) {
            sendResponse("success", "Product found", $product);
        } else {
            sendResponse("error", "Product not found", null, 404);
        }
    }
    
    public function getByCategory($category_id) {
        $products = $this->product->getByCategory($category_id);
        sendResponse("success", "Products retrieved", $products);
    }
    
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!isset($data['category_id']) || !isset($data['product_name']) || !isset($data['price'])) {
            sendResponse("error", "Missing required fields: category_id, product_name, price", null, 400);
        }
        
        $this->product->category_id = $data['category_id'];
        $this->product->product_name = $data['product_name'];
        $this->product->price = $data['price'];
        $this->product->stock_quantity = $data['stock_quantity'] ?? 0;
        $this->product->description = $data['description'] ?? '';
        $this->product->image_url = $data['image_url'] ?? '';
        
        if($this->product->create()) {
            sendResponse("success", "Product created", null, 201);
        } else {
            sendResponse("error", "Failed to create product", null, 500);
        }
    }
    
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $this->product->product_id = $id;
        $this->product->category_id = $data['category_id'] ?? null;
        $this->product->product_name = $data['product_name'] ?? '';
        $this->product->price = $data['price'] ?? 0;
        $this->product->stock_quantity = $data['stock_quantity'] ?? 0;
        $this->product->description = $data['description'] ?? '';
        $this->product->image_url = $data['image_url'] ?? '';
        
        if($this->product->update()) {
            sendResponse("success", "Product updated", null);
        } else {
            sendResponse("error", "Update failed", null, 500);
        }
    }
    
    public function delete($id) {
        if($this->product->delete($id)) {
            sendResponse("success", "Product deleted", null);
        } else {
            sendResponse("error", "Delete failed", null, 500);
        }
    }
}
?>