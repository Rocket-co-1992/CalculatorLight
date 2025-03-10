<?php
namespace Models;

class Inventory {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function checkStock($productId, $quantity) {
        $stmt = $this->db->prepare("SELECT stock_quantity FROM inventory WHERE product_id = ?");
        $stmt->execute([$productId]);
        $result = $stmt->fetch();
        return $result && $result['stock_quantity'] >= $quantity;
    }

    public function updateStock($productId, $quantity, $operation = 'subtract') {
        $sql = "UPDATE inventory SET 
                stock_quantity = stock_quantity " . ($operation === 'subtract' ? '-' : '+') . " :quantity,
                updated_at = NOW()
                WHERE product_id = :product_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['quantity' => $quantity, 'product_id' => $productId]);
    }
}
