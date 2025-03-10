<?php
namespace Controllers;

use Core\Request;
use Models\Cart;

class CartController {
    private $cart;
    private $request;

    public function __construct(Request $request) {
        $this->cart = new Cart();
        $this->request = $request;
    }

    public function add() {
        $productId = $this->request->getParam('product_id');
        $quantity = $this->request->getParam('quantity', 1);
        $designData = $this->request->getParam('design_data', []);
        
        $result = $this->cart->addItem($productId, $quantity, $designData);
        return ['success' => $result];
    }

    public function view() {
        $items = $this->cart->getItems();
        return [
            'template' => 'cart/view.twig',
            'data' => ['items' => $items]
        ];
    }

    public function checkout() {
        if (!$this->cart->hasItems()) {
            return ['error' => 'Cart is empty'];
        }
        
        $shipping = new \Models\Shipping();
        $address = $this->getDefaultShippingAddress();
        $shippingRates = $shipping->calculateShipping($this->cart->getItems(), $address);
        
        return [
            'template' => 'cart/checkout.twig',
            'data' => [
                'items' => $this->cart->getItems(),
                'total' => $this->cart->getTotal(),
                'shipping' => $shippingRates,
                'address' => $address
            ]
        ];
    }

    public function completeOrder() {
        $orderTracker = new \Core\OrderTracker();
        $orderId = $this->cart->convertToOrder();
        $orderTracker->updateOrderStatus($orderId, 'processing');
        
        return [
            'success' => true,
            'redirect' => "/order/tracking/{$orderId}"
        ];
    }
}
