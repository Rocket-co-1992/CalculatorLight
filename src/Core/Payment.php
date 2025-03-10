<?php
namespace Core;

class Payment {
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    public function processPayment($orderId, $method, $data) {
        switch ($method) {
            case 'credit_card':
                return $this->processCreditCard($data);
            case 'mbway':
                return $this->processMBWay($data);
            case 'multibanco':
                return $this->generateMultibancoReference($orderId);
            default:
                throw new \Exception('Invalid payment method');
        }
    }

    private function processCreditCard($data) {
        // Implement credit card processing
        return ['status' => 'success', 'transaction_id' => uniqid('tx_')];
    }

    private function processMBWay($data) {
        // Implement MB WAY processing
        return ['status' => 'pending', 'reference' => uniqid('mb_')];
    }

    private function generateMultibancoReference($orderId) {
        // Generate Multibanco reference
        return [
            'entity' => '12345',
            'reference' => sprintf('%09d', $orderId),
            'amount' => $amount
        ];
    }
}
