<?php
namespace Controllers;

class DashboardController {
    private $request;

    public function __construct(\Core\Request $request) {
        $this->request = $request;
    }

    public function index() {
        $analytics = new \Services\Analytics();
        return [
            'template' => 'dashboard/index.twig',
            'data' => [
                'stats' => $analytics->getDashboardStats(),
                'recent_orders' => $this->getRecentOrders(),
                'low_stock' => $this->getLowStockItems()
            ]
        ];
    }
}
