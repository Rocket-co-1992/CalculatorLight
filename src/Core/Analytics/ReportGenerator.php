<?php
namespace Core\Analytics;

class ReportGenerator {
    private $db;
    private $cache;
    private $config;

    public function __construct($startDate = null, $endDate = null) {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->cache = new \Core\Cache\FileCache();
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->startDate = $startDate ?? date('Y-m-01');
        $this->endDate = $endDate ?? date('Y-m-d');
    }

    public function generateSalesReport($startDate, $endDate) {
        $cacheKey = "sales_report_{$startDate}_{$endDate}";
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $report = [
            'summary' => $this->getSalesSummary($startDate, $endDate),
            'by_product' => $this->getSalesByProduct($startDate, $endDate),
            'by_customer' => $this->getSalesByCustomer($startDate, $endDate),
            'trends' => $this->analyzeSalesTrends($startDate, $endDate)
        ];

        $this->cache->store($cacheKey, $report, 3600); // Cache for 1 hour
        return $report;
    }

    private function getSalesSummary($startDate, $endDate) {
        return $this->db->query("
            SELECT 
                COUNT(*) as total_orders,
                SUM(total_price) as revenue,
                AVG(total_price) as avg_order_value,
                COUNT(DISTINCT user_id) as unique_customers
            FROM orders
            WHERE created_at BETWEEN :start AND :end
            AND status != 'cancelled'
        ", ['start' => $startDate, 'end' => $endDate])->fetch();
    }

    public function generateProductionReport() {
        $cacheKey = "production_report_{$this->startDate}_{$this->endDate}";
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $report = [
            'efficiency' => $this->getProductionEfficiency(),
            'utilization' => $this->getMachineUtilization(),
            'bottlenecks' => $this->getProductionBottlenecks(),
            'material_usage' => $this->getMaterialAnalytics(),
            'quality_metrics' => $this->getQualityMetrics(),
            'cost_analysis' => $this->getCostAnalysis()
        ];

        $this->cache->store($cacheKey, $report, 3600);
        return $report;
    }

    private function getMaterialAnalytics() {
        return $this->db->query("
            SELECT 
                m.material_type,
                SUM(m.quantity_used) as total_used,
                SUM(m.waste_amount) as total_waste,
                AVG(m.waste_amount / m.quantity_used * 100) as waste_percentage
            FROM material_usage m
            WHERE m.created_at BETWEEN :start AND :end
            GROUP BY m.material_type
        ", ['start' => $this->startDate, 'end' => $this->endDate])->fetchAll();
    }

    private function getQualityMetrics() {
        return $this->db->query("
            SELECT 
                COUNT(*) as total_checks,
                SUM(CASE WHEN status = 'passed' THEN 1 ELSE 0 END) as passed_checks,
                check_type,
                AVG(CASE WHEN pm.quality_score IS NOT NULL 
                    THEN pm.quality_score ELSE NULL END) as avg_quality_score
            FROM quality_checks qc
            LEFT JOIN production_metrics pm ON qc.order_id = pm.job_id
            WHERE qc.created_at BETWEEN :start AND :end
            GROUP BY check_type
        ", ['start' => $this->startDate, 'end' => $this->endDate])->fetchAll();
    }
}
