<?php
namespace Services;

class JobScheduler {
    private $db;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function scheduleJob($orderId) {
        $order = new \Models\Order();
        $orderData = $order->getById($orderId);
        
        // Calculate estimated production time
        $estimatedTime = $this->calculateProductionTime($orderData);
        
        // Find next available slot
        $slot = $this->findAvailableSlot($estimatedTime);
        
        // Schedule the job
        return $this->createSchedule([
            'order_id' => $orderId,
            'start_time' => $slot['start'],
            'end_time' => $slot['end'],
            'machine_id' => $slot['machine_id']
        ]);
    }
    
    private function calculateProductionTime($orderData) {
        $baseTime = $orderData['quantity'] * $orderData['product']['time_per_unit'];
        $setupTime = $this->getSetupTime($orderData['finishing_options']);
        
        return $baseTime + $setupTime;
    }
    
    private function findAvailableSlot($requiredTime) {
        $sql = "SELECT m.id, m.next_available_time 
                FROM machines m 
                WHERE m.status = 'active' 
                ORDER BY m.next_available_time ASC 
                LIMIT 1";
                
        $machine = $this->db->query($sql)->fetch();
        
        return [
            'machine_id' => $machine['id'],
            'start' => $machine['next_available_time'],
            'end' => date('Y-m-d H:i:s', strtotime($machine['next_available_time'] . " + {$requiredTime} minutes"))
        ];
    }
}
