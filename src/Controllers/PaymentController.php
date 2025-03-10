<?php
namespace Controllers;

use Core\Request;
use Core\Payment\PaymentProcessor;
use Models\Order;

class PaymentController {
    private $request;
    private $processor;
    private $order;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->processor = new PaymentProcessor($this->getGateway());
        $this->order = new Order();
    }

    public function process() {
        try {
            $orderId = $this->request->getParam('order_id');
            $paymentMethod = $this->request->getParam('payment_method');
            $paymentData = $this->request->getParam('payment_data');

            $result = $this->processor->processOrderPayment($orderId, [
                'method' => $paymentMethod,
                'amount' => $this->getOrderAmount($orderId),
                'data' => $paymentData
            ]);

            if ($result['success']) {
                $this->order->updatePaymentStatus($orderId, 'paid');
                return [
                    'success' => true,
                    'redirect' => "/order/confirmation/{$orderId}"
                ];
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
