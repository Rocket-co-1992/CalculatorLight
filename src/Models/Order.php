<?php
namespace Models;

class Order {
    private $db;
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PRODUCTION = 'production';
    const STATUS_COMPLETED = 'completed';
    const STATUS_SHIPPED = 'shipped';

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function create($orderData) {
        $sql = "INSERT INTO orders (
            user_id, design_id, product_id, quantity, 
            total_price, status, created_at
        ) VALUES (
            :user_id, :design_id, :product_id, :quantity,
            :total_price, :status, NOW()
        )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $orderData['user_id'],
            'design_id' => $orderData['design_id'],
            'product_id' => $orderData['product_id'],
            'quantity' => $orderData['quantity'],
            'total_price' => $orderData['total_price'],
            'status' => self::STATUS_PENDING
        ]);
        
        return $this->db->lastInsertId();
    }

    public function updateStatus($orderId, $status) {
        $sql = "UPDATE orders SET 
                status = :status,
                updated_at = NOW()
                WHERE id = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $orderId,
            'status' => $status
        ]);
    }

    public function getProductionQueue() {
        $sql = "SELECT o.*, p.name as product_name, u.email as user_email
                FROM orders o
                JOIN products p ON o.product_id = p.id
                JOIN users u ON o.user_id = u.id
                WHERE o.status = :status
                ORDER BY o.created_at ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['status' => self::STATUS_PROCESSING]);
        return $stmt->fetchAll();
    }
}
