<?php
namespace Core\Automation;

use Core\Database;
use Core\Production\Workflow;

class BatchProcessor {
    private $db;
    private $workflow;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->workflow = new Workflow();
    }

    public function processOrderBatch() {
        $orders = $this->getPendingOrders();
        $batches = $this->optimizeBatches($orders);
        
        foreach ($batches as $batch) {
            $this->assignToPrinters($batch);
        }
        
        return count($batches);
    }

    private function getPendingOrders() {
        return $this->db->query(
            "SELECT o.*, oi.* FROM orders o 
             JOIN order_items oi ON o.id = oi.order_id 
             WHERE o.status = 'pending' 
             ORDER BY o.created_at ASC"
        )->fetchAll();
    }

    private function optimizeBatches($orders) {
        $batches = [];
        foreach ($orders as $order) {
            $key = $this->getBatchKey($order);
            $batches[$key][] = $order;
        }
        return $batches;
    }

    private function getBatchKey($order) {
        return $order['product_id'] . '_' . 
               md5(json_encode($order['design_data']));
    }
}
