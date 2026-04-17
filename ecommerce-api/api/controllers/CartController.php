<?php
require_once __DIR__ . '/../inc/response.php';
require_once __DIR__ . '/../models/Cart.php';

class CartController {
    private $cart;
    
    public function __construct($db) {
        $this->cart = new Cart($db);
    }
    
    public function getCart($user_id) {
        $cart = $this->cart->getCart($user_id);
        sendResponse("success", "Cart retrieved", $cart);
    }
    
    public function addToCart($user_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!isset($data['product_id']) || !isset($data['quantity'])) {
            sendResponse("error", "product_id and quantity required", null, 400);
        }
        
        if($this->cart->addToCart($user_id, $data['product_id'], $data['quantity'])) {
            sendResponse("success", "Item added to cart", null);
        } else {
            sendResponse("error", "Failed to add item", null, 500);
        }
    }
    
    public function removeFromCart($user_id, $cart_item_id) {
        if($this->cart->removeFromCart($user_id, $cart_item_id)) {
            sendResponse("success", "Item removed from cart", null);
        } else {
            sendResponse("error", "Failed to remove item", null, 500);
        }
    }
    
    public function clearCart($user_id) {
        if($this->cart->clearCart($user_id)) {
            sendResponse("success", "Cart cleared", null);
        } else {
            sendResponse("error", "Failed to clear cart", null, 500);
        }
    }
}
?>