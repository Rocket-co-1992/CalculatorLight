<?php
namespace Models;

class QualityControl {
    private $db;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function approveOrder($orderId, $data) {
        $sql = "INSERT INTO quality_checks (
            order_id, approved_by, notes, status, created_at
        ) VALUES (
            :order_id, :approved_by, :notes, 'approved', NOW()
        )";
        
        $result = $this->db->prepare($sql)->execute([
            'order_id' => $orderId,
            'approved_by' => $data['approved_by'],
            'notes' => $data['notes']
        ]);
        
        if ($result) {
            $order = new Order();
            return $order->updateStatus($orderId, 'production');
        }
        
        return false;
    }
    
    public function getQualityHistory($orderId) {
        $sql = "SELECT qc.*, u.name as inspector_name 
                FROM quality_checks qc
                JOIN users u ON qc.approved_by = u.id
                WHERE qc.order_id = ?
                ORDER BY qc.created_at DESC";
                
        return $this->db->query($sql, [$orderId])->fetchAll();
    }
}
