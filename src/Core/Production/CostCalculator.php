<?php
namespace Core\Production;

class CostCalculator {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function calculateJobCost($orderId) {
        $order = $this->getOrderDetails($orderId);
        
        return [
            'materials' => $this->calculateMaterialCosts($order),
            'printing' => $this->calculatePrintingCosts($order),
            'finishing' => $this->calculateFinishingCosts($order),
            'labor' => $this->calculateLaborCosts($order),
            'overhead' => $this->calculateOverheadCosts($order)
        ];
    }

    private function calculateMaterialCosts($order) {
        $materialTracker = new MaterialTracker();
        $materialUsage = $this->estimateMaterialUsage($order);
        
        $costs = 0;
        foreach ($materialUsage as $material) {
            $materialCost = $this->getMaterialCost($material['type']);
            $costs += $material['quantity'] * $materialCost;
            $materialTracker->trackMaterialUsage($order['id'], [
                'type' => $material['type'],
                'quantity' => $material['quantity'],
                'cost' => $materialCost
            ]);
        }
        
        return $costs;
    }

    private function estimateMaterialUsage($order) {
        return [
            [
                'type' => 'paper',
                'quantity' => $this->calculatePaperNeeded($order),
                'waste' => $this->estimateWaste($order, 'paper')
            ],
            [
                'type' => 'ink',
                'quantity' => $this->calculateInkNeeded($order),
                'waste' => $this->estimateWaste($order, 'ink')
            ]
        ];
    }

    private function getMaterialCost($materialType) {
        $sql = "SELECT unit_cost FROM material_inventory WHERE material_type = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$materialType]);
        return $stmt->fetchColumn() ?? 0;
    }

    private function calculatePrintingCosts($order) {
        return [
            'machine_time' => $this->calculateMachineTime($order) * $this->getHourlyRate(),
            'setup' => $this->calculateSetupCost($order),
            'maintenance' => $this->calculateMaintenanceCost($order)
        ];
    }

    private function getHourlyRate() {
        // Get machine hourly rate from configuration
        return $this->config['production']['hourly_rates']['printer'];
    }
}
