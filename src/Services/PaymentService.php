<?php
namespace Services;

class PaymentService {
    private $config;
    private $db;

    public function __construct() {
        $this->config = require_once __DIR__ . '/../../config/config.php';
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function createMBWayPayment($order, $phoneNumber) {
        $apiKey = $this->config['payment']['mbway']['api_key'];
        $amount = $order['total_amount'];
        
        $response = $this->sendMBWayRequest([
            'phone' => $phoneNumber,
            'amount' => $amount,
            'order_id' => $order['id']
        ]);

        $this->logPaymentAttempt($order['id'], 'mbway', $response);
        return $response;
    }

    public function createMultibancoPayment($order) {
        $entity = $this->config['payment']['multibanco']['entity'];
        $subentity = $this->config['payment']['multibanco']['subentity'];
        
        $reference = $this->generateMultibancoReference($order['id']);
        
        return [
            'entity' => $entity,
            'reference' => $reference,
            'amount' => $order['total_amount']
        ];
    }

    private function generateMultibancoReference($orderId) {
        // Implement Multibanco reference generation algorithm
        return str_pad($orderId, 9, '0', STR_PAD_LEFT);
    }
}
