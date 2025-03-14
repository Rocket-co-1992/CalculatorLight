<?php
namespace Controllers;

class ProductionController {
    private $request;
    private $db;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function getDashboard() {
        $production = new \Models\Production();
        
        return [
            'template' => 'production/dashboard.twig',
            'data' => [
                'pending_orders' => $production->getPendingOrders(),
                'in_production' => $production->getInProduction(),
                'machine_status' => $production->getMachineStatus(),
                'daily_stats' => $production->getDailyStats()
            ]
        ];
    }
    
    public function assignToMachine() {
        $orderId = $this->request->getParam('order_id');
        $machineId = $this->request->getParam('machine_id');
        
        $production = new \Models\Production();
        $result = $production->assignOrderToMachine($orderId, $machineId);
        
        return $this->jsonResponse(['success' => $result]);
    }
    
    private function jsonResponse($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
