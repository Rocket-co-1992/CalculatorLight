<?php
namespace Core\Payment;

class PaymentProcessor {
    private $db;
    private $gateway;
    private $logger;

    public function __construct(PaymentGateway $gateway) {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->gateway = $gateway;
        $this->logger = new \Core\Logger();
    }

    public function processOrderPayment($orderId, $paymentData) {
        try {
            $this->db->beginTransaction();
            
            $result = $this->gateway->processPayment($paymentData);
            if ($result['success']) {
                $this->updateOrderPaymentStatus($orderId, 'paid', $result['transaction_id']);
            }
            
            $this->db->commit();
            return $result;
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->logger->logError($e);
            throw $e;
        }
    }

    private function updateOrderPaymentStatus($orderId, $status, $transactionId) {
        // Update order payment status in the database
        $stmt = $this->db->prepare("UPDATE orders SET status = :status, transaction_id = :transaction_id WHERE id = :order_id");
        $stmt->execute([
            ':status' => $status,
            ':transaction_id' => $transactionId,
            ':order_id' => $orderId
        ]);
    }
}
