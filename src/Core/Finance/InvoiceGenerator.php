<?php
namespace Core\Finance;

class InvoiceGenerator {
    private $db;
    private $config;
    private $pdfGenerator;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->pdfGenerator = new \Core\PDF\PDFGenerator();
    }

    public function generateInvoice($orderId) {
        $order = $this->getOrderDetails($orderId);
        $invoiceData = $this->prepareInvoiceData($order);
        
        $invoiceNumber = $this->createInvoiceRecord($invoiceData);
        return $this->generatePDF($invoiceNumber, $invoiceData);
    }

    private function prepareInvoiceData($order) {
        return [
            'order_id' => $order['id'],
            'items' => $this->getOrderItems($order['id']),
            'subtotal' => $order['total_price'],
            'vat' => $this->calculateVAT($order['total_price']),
            'total' => $order['total_price'] * (1 + $this->config['finance']['vat_rate'])
        ];
    }
}
