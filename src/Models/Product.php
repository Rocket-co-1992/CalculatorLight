<?php
namespace Models;

class Product {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO products (name, description, base_price, category_id, created_at) 
                VALUES (:name, :description, :base_price, :category_id, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'base_price' => $data['base_price'],
            'category_id' => $data['category_id']
        ]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function calculatePrice($productId, $options) {
        $product = $this->getById($productId);
        $basePrice = $product['base_price'];
        
        // Add price calculations based on options (size, material, quantity, etc.)
        // This is a simple example - expand based on your needs
        $finalPrice = $basePrice * $options['quantity'];
        if (!empty($options['finishing'])) {
            $finalPrice += $this->calculateFinishingCosts($options['finishing']);
        }
        
        return $finalPrice;
    }

    private function calculateFinishingCosts($finishing) {
        // Add your finishing cost calculation logic here
        return 0;
    }
}
