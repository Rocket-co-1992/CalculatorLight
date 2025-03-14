<?php
namespace Controllers;

use Core\Request;
use Models\Order;
use Models\Product;

class OrderController {
    private $order;
    private $request;

    public function __construct(Request $request) {
        $this->order = new Order();
        $this->request = $request;
    }

    public function create() {
        $product = new Product();
        return [
            'template' => 'order/create.twig',
            'data' => [
                'products' => $product->getAllActive(),
                'materials' => $product->getMaterials(),
                'finishing_options' => $this->getFinishingOptions()
            ]
        ];
    }

    public function process() {
        $orderData = $this->request->getParams();

        // Validate order data
        if (!$this->validateOrderData($orderData)) {
            return $this->jsonResponse([
                'success' => false,
                'errors' => $this->getValidationErrors()
            ]);
        }

        // Create order
        $order = new Order();
        $result = $order->create($orderData);

        return $this->jsonResponse([
            'success' => true,
            'order_id' => $result
        ]);
    }

    private function calculateTotal($items) {
        return array_reduce($items, function($total, $item) {
            return $total + ($item['price'] * $item['quantity']);
        }, 0);
    }
}
