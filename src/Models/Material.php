<?php
namespace Models;

class Material {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO materials (
            name, type, weight_gsm, thickness_microns, 
            cost_per_m2, color_profile, stock_level
        ) VALUES (
            :name, :type, :weight_gsm, :thickness_microns,
            :cost_per_m2, :color_profile, :stock_level
        )";
        
        return $this->db->prepare($sql)->execute([
            'name' => $data['name'],
            'type' => $data['type'],
            'weight_gsm' => $data['weight_gsm'],
            'thickness_microns' => $data['thickness_microns'],
            'cost_per_m2' => $data['cost_per_m2'],
            'color_profile' => $data['color_profile'],
            'stock_level' => $data['stock_level']
        ]);
    }

    public function updateStock($materialId, $quantity, $operation = 'subtract') {
        $sql = "UPDATE materials SET stock_level = stock_level " . 
               ($operation === 'add' ? '+' : '-') . 
               " :quantity WHERE id = :id";
        
        return $this->db->prepare($sql)->execute([
            'id' => $materialId,
            'quantity' => $quantity
        ]);
    }

    public function getLowStockMaterials($threshold = 100) {
        $sql = "SELECT * FROM materials WHERE stock_level < :threshold";
        return $this->db->query($sql, ['threshold' => $threshold])->fetchAll();
    }

    public function getAllRates() {
        $sql = "SELECT m.id, mr.base_rate, mr.min_quantity, mr.discount_rate
                FROM materials m
                JOIN material_rates mr ON m.id = mr.material_id
                WHERE m.active = 1";
        
        $rates = [];
        $result = $this->db->query($sql)->fetchAll();
        
        foreach ($result as $row) {
            $rates[$row['id']] = [
                'base_rate' => $row['base_rate'],
                'min_quantity' => $row['min_quantity'],
                'discount_rate' => $row['discount_rate']
            ];
        }
        
        return $rates;
    }
    
    public function updateRate($materialId, $data) {
        $sql = "UPDATE material_rates 
                SET base_rate = :base_rate,
                    min_quantity = :min_quantity,
                    discount_rate = :discount_rate,
                    updated_at = NOW()
                WHERE material_id = :material_id";
                
        return $this->db->prepare($sql)->execute([
            'material_id' => $materialId,
            'base_rate' => $data['base_rate'],
            'min_quantity' => $data['min_quantity'],
            'discount_rate' => $data['discount_rate']
        ]);
    }
}
