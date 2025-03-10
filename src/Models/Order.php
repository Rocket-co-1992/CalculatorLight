<?php
namespace Models;

class Order {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function create($userId, $items, $totalPrice) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO orders (user_id, total_price, status) VALUES (:user_id, :total_price, 'pending')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $userId,
                'total_price' => $totalPrice
            ]);
            
            $orderId = $this->db->lastInsertId();
            $this->addOrderItems($orderId, $items);
            
            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function addOrderItems($orderId, $items) {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price, design_data) 
                VALUES (:order_id, :product_id, :quantity, :price, :design_data)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($items as $item) {
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'design_data' => json_encode($item['design_data'])
            ]);
        }
    }
}
