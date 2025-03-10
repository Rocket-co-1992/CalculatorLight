<?php
namespace Core\Payment;

interface PaymentGateway {
    public function processPayment(array $paymentData): array;
    public function validateTransaction(string $transactionId): bool;
    public function refundPayment(string $transactionId, float $amount): bool;
}
