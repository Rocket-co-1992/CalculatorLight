<?php
namespace Controllers;

class CustomerPortalController {
    private $request;
    
    public function __construct(\Core\Request $request) {
        $this->request = $request;
        $this->validateCustomerAccess();
    }
    
    public function dashboard() {
        $customer = new \Models\Customer();
        $customerId = $this->request->getCustomerId();
        
        return [
            'template' => 'customer/dashboard.twig',
            'data' => [
                'recent_orders' => $customer->getRecentOrders($customerId),
                'saved_designs' => $customer->getSavedDesigns($customerId),
                'quotes' => $customer->getPendingQuotes($customerId)
            ]
        ];
    }
    
    public function orderHistory() {
        $orders = new \Models\Order();
        return [
            'template' => 'customer/orders.twig',
            'data' => [
                'orders' => $orders->getCustomerOrders($this->request->getCustomerId())
            ]
        ];
    }
    
    private function validateCustomerAccess() {
        if (!$this->request->getCustomerId()) {
            header('Location: /login');
            exit;
        }
    }
}
