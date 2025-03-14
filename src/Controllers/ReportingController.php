<?php
namespace Controllers;

class ReportingController {
    private $request;
    private $analytics;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
        $this->analytics = new \Services\Analytics();
    }
    
    public function dashboard() {
        return [
            'template' => 'reporting/dashboard.twig',
            'data' => [
                'sales_overview' => $this->getSalesOverview(),
                'top_products' => $this->getTopProducts(),
                'production_stats' => $this->getProductionStats(),
                'inventory_alerts' => $this->getInventoryAlerts()
            ]
        ];
    }
    
    private function getSalesOverview() {
        $orders = new \Models\Order();
        return [
            'daily' => $orders->getDailySales(),
            'monthly' => $orders->getMonthlySales(),
            'yearly' => $orders->getYearlySales()
        ];
    }
    
    private function getTopProducts() {
        return $this->analytics->getProductPerformance();
    }
    
    private function getProductionStats() {
        $production = new \Models\Production();
        return [
            'completed_jobs' => $production->getCompletedJobs(),
            'pending_jobs' => $production->getPendingJobs(),
            'machine_utilization' => $production->getMachineUtilization()
        ];
    }
}
