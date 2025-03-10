<?php
namespace Core;

class ProductionWorkflow {
    private $db;
    private $orderTracker;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->orderTracker = new OrderTracker();
    }

    public function assignToPrinter($orderId, $printerId) {
        $sql = "INSERT INTO production_queue (order_id, printer_id, status) 
                VALUES (:order_id, :printer_id, 'queued')";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'order_id' => $orderId,
            'printer_id' => $printerId
        ]);

        if ($result) {
            $this->orderTracker->updateOrderStatus($orderId, 'in_production');
        }
        
        return $result;
    }

    public function updateProductionStatus($orderId, $status, $notes = '') {
        $sql = "UPDATE production_queue 
                SET status = :status, notes = :notes, updated_at = NOW() 
                WHERE order_id = :order_id";
                
        return $this->db->prepare($sql)->execute([
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes
        ]);
    }
}
