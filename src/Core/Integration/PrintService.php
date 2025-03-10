<?php
namespace Core\Integration;

class PrintService {
    private $config;
    private $client;

    public function __construct() {
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->client = new \Core\Http\Client();
    }

    public function sendPrintJob($orderId) {
        $order = $this->getOrderDetails($orderId);
        $printJob = $this->preparePrintJob($order);
        
        return $this->client->post(
            $this->config['print_service']['endpoint'],
            $printJob,
            [
                'Authorization' => 'Bearer ' . $this->config['print_service']['api_key'],
                'Content-Type' => 'application/json'
            ]
        );
    }

    private function preparePrintJob($order) {
        return [
            'job_id' => uniqid('print_'),
            'files' => $this->prepareFiles($order['files']),
            'specifications' => $this->getSpecifications($order),
            'delivery' => $this->getDeliveryInfo($order)
        ];
    }
}
