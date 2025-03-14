<?php
namespace Models;

class Report {
    private $db;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function generateSalesReport($params) {
        $sql = "SELECT DATE(o.created_at) as date,
                COUNT(o.id) as total_orders,
                SUM(o.total_amount) as revenue,
                COUNT(DISTINCT o.customer_id) as unique_customers
                FROM orders o
                WHERE o.created_at BETWEEN :start AND :end
                GROUP BY DATE(o.created_at)
                ORDER BY date ASC";
                
        return $this->db->query($sql, [
            'start' => $params['start_date'],
            'end' => $params['end_date']
        ])->fetchAll();
    }
    
    public function generateProductionReport($params) {
        $sql = "SELECT m.name as machine,
                COUNT(j.id) as total_jobs,
                SUM(j.duration) as total_hours,
                AVG(j.setup_time) as avg_setup_time
                FROM production_jobs j
                JOIN machines m ON j.machine_id = m.id
                WHERE j.completed_at BETWEEN :start AND :end
                GROUP BY m.id";
                
        return $this->db->query($sql, [
            'start' => $params['start_date'],
            'end' => $params['end_date']
        ])->fetchAll();
    }
}
