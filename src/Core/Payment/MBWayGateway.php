<?php
namespace Core\Payment;

class MBWayGateway implements PaymentGateway {
    private $config;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../../../config/config.php';
    }

    public function processPayment(array $paymentData): array {
        // Implement MB WAY API integration
        return [
            'success' => true,
            'transaction_id' => uniqid('mbw_'),
            'reference' => sprintf('%09d', rand(1, 999999999)),
            'amount' => $paymentData['amount']
        ];
    }

    public function validateTransaction(string $transactionId): bool {
        // Implement transaction validation
        return true;
    }

    public function refundPayment(string $transactionId, float $amount): bool {
        // Implement refund logic
        return true;
    }
}
