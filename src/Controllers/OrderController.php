<?php
namespace Controllers;

use Core\Request;
use Models\Order;

class OrderController {
    private $order;
    private $request;

    public function __construct(Request $request) {
        $this->order = new Order();
        $this->request = $request;
    }

    public function create() {
        if (!$this->request->isAjax()) {
            return ['error' => 'Invalid request'];
        }

        $items = $this->request->getParam('items');
        $userId = $_SESSION['user_id'];
        $totalPrice = $this->calculateTotal($items);

        try {
            $orderId = $this->order->create($userId, $items, $totalPrice);
            return [
                'success' => true,
                'order_id' => $orderId,
                'redirect' => "/payment/{$orderId}"
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function calculateTotal($items) {
        return array_reduce($items, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
