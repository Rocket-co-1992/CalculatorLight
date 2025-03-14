<?php
namespace Models;

class Inventory {
    private $db;
    
    const STOCK_LOW = 'low';
    const STOCK_NORMAL = 'normal';
    const STOCK_CRITICAL = 'critical';
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function updateStock($materialId, $quantity, $operation = 'subtract') {
        $sign = $operation === 'add' ? '+' : '-';
        $sql = "UPDATE materials SET 
                stock_quantity = stock_quantity {$sign} :quantity,
                last_updated = NOW()
                WHERE id = :id";
                
        return $this->db->prepare($sql)->execute([
            'id' => $materialId,
            'quantity' => $quantity
        ]);
    }
    
    public function checkStockLevel($materialId) {
        $sql = "SELECT m.*, mt.min_stock_level 
                FROM materials m
                JOIN material_types mt ON m.type_id = mt.id
                WHERE m.id = :id";
                
        $material = $this->db->prepare($sql)->execute(['id' => $materialId])->fetch();
        
        if ($material['stock_quantity'] <= $material['min_stock_level'] * 0.2) {
            return self::STOCK_CRITICAL;
        } elseif ($material['stock_quantity'] <= $material['min_stock_level']) {
            return self::STOCK_LOW;
        }
        
        return self::STOCK_NORMAL;
    }
    
    public function getLowStockMaterials() {
        $sql = "SELECT m.*, mt.min_stock_level 
                FROM materials m
                JOIN material_types mt ON m.type_id = mt.id
                WHERE m.stock_quantity <= mt.min_stock_level";
                
        return $this->db->query($sql)->fetchAll();
    }
}
