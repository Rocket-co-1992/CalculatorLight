<?php
namespace Core\Production;

class MaterialTracker {
    private $db;
    private $config;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../../config/config.php';
    }

    public function trackMaterialUsage($orderId, $materialData) {
        $sql = "INSERT INTO material_usage (order_id, material_type, quantity_used, waste_amount) 
                VALUES (:order_id, :type, :quantity, :waste)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'order_id' => $orderId,
            'type' => $materialData['type'],
            'quantity' => $materialData['quantity'],
            'waste' => $materialData['waste']
        ]);
    }

    public function getWasteMetrics($startDate, $endDate) {
        $sql = "SELECT material_type, 
                       SUM(quantity_used) as total_used,
                       SUM(waste_amount) as total_waste,
                       (SUM(waste_amount) / SUM(quantity_used)) * 100 as waste_percentage
                FROM material_usage
                WHERE created_at BETWEEN :start AND :end
                GROUP BY material_type";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        return $stmt->fetchAll();
    }
}
