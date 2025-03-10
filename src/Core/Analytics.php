<?php
namespace Core;

class Analytics {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getSalesReport($startDate, $endDate) {
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as total_orders,
                    SUM(total_price) as revenue
                FROM orders 
                WHERE created_at BETWEEN :start AND :end
                GROUP BY DATE(created_at)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        return $stmt->fetchAll();
    }

    public function getProductPerformance() {
        $sql = "SELECT 
                    p.name,
                    COUNT(oi.id) as total_sold,
                    SUM(oi.quantity) as units_sold,
                    SUM(oi.price * oi.quantity) as revenue
                FROM products p
                LEFT JOIN order_items oi ON p.id = oi.product_id
                GROUP BY p.id
                ORDER BY revenue DESC";

        return $this->db->query($sql)->fetchAll();
    }
}
