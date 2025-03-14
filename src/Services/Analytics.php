<?php
namespace Services;

class Analytics {
    private $db;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function trackEvent($event, $data) {
        $sql = "INSERT INTO analytics_events (
            event_type, data, user_id, ip_address, created_at
        ) VALUES (
            :event_type, :data, :user_id, :ip_address, NOW()
        )";
        
        return $this->db->prepare($sql)->execute([
            'event_type' => $event,
            'data' => json_encode($data),
            'user_id' => $_SESSION['user_id'] ?? null,
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ]);
    }
    
    public function getProductPerformance($startDate = null, $endDate = null) {
        $sql = "SELECT p.name, COUNT(o.id) as orders,
                SUM(o.total_amount) as revenue,
                AVG(o.total_amount) as avg_order_value
                FROM products p
                LEFT JOIN orders o ON p.id = o.product_id
                WHERE o.created_at BETWEEN :start AND :end
                GROUP BY p.id
                ORDER BY revenue DESC";
                
        return $this->db->query($sql, [
            'start' => $startDate ?? date('Y-m-d', strtotime('-30 days')),
            'end' => $endDate ?? date('Y-m-d')
        ])->fetchAll();
    }
}
