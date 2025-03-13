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

    private function validateApiKey() {
        $apiKey = $this->request->getHeader('X-API-Key');
        if (!$apiKey) {
            throw new \Exception('API key required', 401);
        }
        // Validate against stored API keys
        return true;
    }

    private function jsonResponse($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        return json_encode($data);
    }

    private function validateWebhookSignature() {
        $signature = $this->request->getHeader('X-Webhook-Signature');
        if (!$signature) {
            return false;
        }
        // Implement signature validation
        return true;
    }

    public function webhooks() {
        if (!$this->validateWebhookSignature()) {
            throw new \Exception('Invalid webhook signature');
        }

        $event = $this->request->getParam('event');
        $data = $this->request->getParam('data');

        switch ($event) {
            case 'payment.success':
                $order = new \Models\Order();
                $order->processPayment($data);
                return $this->jsonResponse(['success' => true]);
                
            case 'order.update':
                $order = new \Models\Order();
                $order->updateStatus($data);
                return $this->jsonResponse(['success' => true]);
                
            default:
                throw new \Exception('Unknown webhook event', 400);
        }
    }
}