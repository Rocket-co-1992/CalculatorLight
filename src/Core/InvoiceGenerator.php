<?php
namespace Core;

class InvoiceGenerator {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    public function generateInvoice($orderId) {
        $order = $this->getOrderDetails($orderId);
        $template = $this->loadInvoiceTemplate();
        
        $invoiceNumber = $this->generateInvoiceNumber();
        $invoiceData = [
            'number' => $invoiceNumber,
            'date' => date('Y-m-d'),
            'order' => $order,
            'company' => $this->config['company'],
            'vat' => $this->calculateVAT($order['total_price'])
        ];
        
        return $this->renderInvoice($template, $invoiceData);
    }

    private function generateInvoiceNumber() {
        return 'INV-' . date('Y') . sprintf('%06d', rand(1, 999999));
    }

    private function calculateVAT($amount) {
        return $amount * 0.23; // 23% VAT rate for Portugal
    }
}
