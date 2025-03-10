<?php
namespace Controllers;

use Core\Auth;
use Core\Request;
use Models\Order;
use Models\Product;

class AdminController {
    private $request;
    
    public function __construct(Request $request) {
        if (!Auth::getInstance()->hasRole('admin')) {
            throw new \Exception('Unauthorized access');
        }
        $this->request = $request;
    }

    public function dashboard() {
        $dashboardManager = new \Core\Analytics\DashboardManager();
        $metrics = $dashboardManager->getBusinessMetrics();

        return [
            'template' => 'admin/dashboard.twig',
            'data' => [
                'stats' => $this->getStats(),
                'metrics' => $metrics,
                'trends' => $this->getBusinessTrends()
            ]
        ];
    }

    private function getBusinessTrends() {
        // Implementation for calculating business trends
    }

    private function getStats() {
        $order = new Order();
        $product = new Product();

        return [
            'daily_sales' => $order->getDailySales(),
            'pending_orders' => $order->countByStatus('pending'),
            'low_stock' => $product->getLowStockProducts(),
            'monthly_revenue' => $order->getMonthlyRevenue()
        ];
    }
}
