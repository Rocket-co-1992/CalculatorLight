<?php
namespace Controllers;

class CheckoutController {
    private $request;
    private $payment;
    private $shipping;

    public function __construct(\Core\Request $request) {
        $this->request = $request;
        $this->payment = new \Services\PaymentService();
        $this->shipping = new \Services\ShippingService();
    }

    public function calculateShipping() {
        $orderId = $this->request->getParam('order_id');
        $address = $this->request->getParam('shipping_address');

        $order = new \Models\Order();
        $orderData = $order->getById($orderId);

        $rates = $this->shipping->calculateShipping($orderData, $address);

        return $this->jsonResponse([
            'success' => true,
            'rates' => $rates
        ]);
    }

    public function processPayment() {
        $method = $this->request->getParam('payment_method');
        $orderId = $this->request->getParam('order_id');

        switch ($method) {
            case 'mbway':
                $phone = $this->request->getParam('phone');
                $result = $this->payment->createMBWayPayment($orderId, $phone);
                break;

            case 'multibanco':
                $result = $this->payment->createMultibancoPayment($orderId);
                break;

            default:
                throw new \Exception('Invalid payment method');
        }

        return $this->jsonResponse([
            'success' => true,
            'payment' => $result
        ]);
    }
}
