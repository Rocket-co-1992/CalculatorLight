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

    public function getAllActive() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE p.active = 1";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getProductOptions($productId) {
        $sql = "SELECT * FROM product_options WHERE product_id = ?";
        return $this->db->query($sql, [$productId])->fetchAll();
    }
    
    public function getMaterials($productId) {
        $sql = "SELECT m.* FROM materials m 
                JOIN product_materials pm ON m.id = pm.material_id 
                WHERE pm.product_id = ?";
        return $this->db->query($sql, [$productId])->fetchAll();
    }

    public function addPrintSpecs($productId, $specs) {
        $sql = "INSERT INTO product_print_specs (
            product_id, min_width, max_width, min_height, max_height,
            min_bleed, color_profile, resolution_dpi
        ) VALUES (
            :product_id, :min_width, :max_width, :min_height, :max_height,
            :min_bleed, :color_profile, :resolution_dpi
        )";
        
        return $this->db->prepare($sql)->execute([
            'product_id' => $productId,
            'min_width' => $specs['min_width'],
            'max_width' => $specs['max_width'],
            'min_height' => $specs['min_height'],
            'max_height' => $specs['max_height'],
            'min_bleed' => $specs['min_bleed'],
            'color_profile' => $specs['color_profile'],
            'resolution_dpi' => $specs['resolution_dpi']
        ]);
    }

    public function getPrintSpecs($productId) {
        $sql = "SELECT * FROM product_print_specs WHERE product_id = ?";
        return $this->db->query($sql, [$productId])->fetch();
    }

    public function getTemplates($productId) {
        $sql = "SELECT t.*, u.username as creator 
                FROM templates t 
                LEFT JOIN users u ON t.created_by = u.id 
                WHERE t.product_id = ? AND t.status = 'active'";
        return $this->db->query($sql, [$productId])->fetchAll();
    }
}
