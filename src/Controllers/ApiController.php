<?php
namespace Controllers;

use Core\Request;
use Core\Auth;

class ApiController {
    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->validateApiKey();
    }

    public function getOrderStatus($orderId) {
        $order = new \Models\Order();
        $status = $order->getStatus($orderId);
        
        return $this->jsonResponse([
            'success' => true,
            'order_id' => $orderId,
            'status' => $status
        ]);
    }

    public function documentation() {
        $docs = new \Core\Api\Documentation();
        
        // Add API endpoints documentation
        $docs->addEndpoint('GET', '/orders/{id}', 'Get order details', [
            'id' => ['type' => 'integer', 'required' => true]
        ]);
        
        $docs->addEndpoint('POST', '/designs/create', 'Create new design', [
            'product_id' => ['type' => 'integer', 'required' => true],
            'design_data' => ['type' => 'object', 'required' => true]
        ]);

        return [
            'template' => 'api/documentation.twig',
            'data' => $docs->generateDocs()
        ];
    }

    public function webhooks() {
        if (!$this->validateWebhookSignature()) {
            throw new \Exception('Invalid webhook signature');
        }

        $event = $this->request->getParam('event');
        $data = $this->request->getParam('data');

        switch ($event) {
            case 'payment.success':