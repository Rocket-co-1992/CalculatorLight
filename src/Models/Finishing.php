<?php
namespace Models;

class Finishing {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function getAllRates() {
        $sql = "SELECT f.id, fr.base_rate, fr.setup_cost, fr.min_quantity
                FROM finishing_types f
                JOIN finishing_rates fr ON f.id = fr.finishing_id
                WHERE f.active = 1";
        
        $rates = [];
        $result = $this->db->query($sql)->fetchAll();
        
        foreach ($result as $row) {
            $rates[$row['id']] = [
                'base_rate' => $row['base_rate'],
                'setup_cost' => $row['setup_cost'],
                'min_quantity' => $row['min_quantity']
            ];
        }
        
        return $rates;
    }
    
    public function updateRate($finishingId, $data) {
        $sql = "UPDATE finishing_rates 
                SET base_rate = :base_rate,
                    setup_cost = :setup_cost,
                    min_quantity = :min_quantity,
                    updated_at = NOW()
                WHERE finishing_id = :finishing_id";
                
        return $this->db->prepare($sql)->execute([
            'finishing_id' => $finishingId,
            'base_rate' => $data['base_rate'],
            'setup_cost' => $data['setup_cost'],
            'min_quantity' => $data['min_quantity']
        ]);
    }
}
