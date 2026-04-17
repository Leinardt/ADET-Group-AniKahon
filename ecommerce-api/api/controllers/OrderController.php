<?php
require_once __DIR__ . '/../inc/response.php';
require_once __DIR__ . '/../models/Order.php';

class OrderController {
    private $order;
    
    public function __construct($db) {
        $this->order = new Order($db);
    }
    
    public function createOrder($user_id) {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!isset($data['shipping_address']) || !isset($data['payment_method'])) {
            sendResponse("error", "shipping_address and payment_method required", null, 400);
        }
        
        $result = $this->order->createOrder($user_id, $data['shipping_address'], $data['payment_method']);
        
        if(isset($result['error'])) {
            sendResponse("error", $result['error'], null, 400);
        } else {
            sendResponse("success", "Order placed successfully", $result, 201);
        }
    }
    
    public function getUserOrders($user_id) {
        $orders = $this->order->getUserOrders($user_id);
        sendResponse("success", "Orders retrieved", $orders);
    }
    
    public function getOrderDetails($user_id, $order_id) {
        $details = $this->order->getOrderDetails($order_id, $user_id);
        if($details) {
            sendResponse("success", "Order details retrieved", $details);
        } else {
            sendResponse("error", "Order not found", null, 404);
        }
    }
}
?>