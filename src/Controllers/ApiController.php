<?php
namespace Controllers;

use Core\Request;
use Core\Auth;

class ApiController {
    // Add constants for HTTP status codes
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_TOO_MANY_REQUESTS = 429;

    private $request;
    private $rateLimiter;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->rateLimiter = new \Core\RateLimiter();
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
            throw new \Exception('API key required', self::HTTP_UNAUTHORIZED);
        }
        if (!$this->checkRateLimit($apiKey)) {
            throw new \Exception('Rate limit exceeded', self::HTTP_TOO_MANY_REQUESTS);
        }
        // Validate against stored API keys
        return true;
    }

    private function checkRateLimit($apiKey) {
        return $this->rateLimiter->check($apiKey);
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

    private function validateWebhookPayload() {
        $requiredFields = ['event', 'data'];
        foreach ($requiredFields as $field) {
            if (!$this->request->hasParam($field)) {
                throw new \Exception("Missing required field: {$field}", self::HTTP_BAD_REQUEST);
            }
        }
        return true;
    }

    public function webhooks() {
        if (!$this->validateWebhookSignature()) {
            throw new \Exception('Invalid webhook signature', self::HTTP_UNAUTHORIZED);
        }

        $this->validateWebhookPayload();

        $event = $this->request->getParam('event');
        $data = $this->request->getParam('data');

        try {
            switch ($event) {
                case 'payment.success':
                    $order = new \Models\Order();
                    $order->processPayment($data);
                    
                    // Schedule job and notify customer
                    $scheduler = new \Services\JobScheduler();
                    $schedule = $scheduler->scheduleJob($data['order_id']);
                    
                    $notifier = new \Services\NotificationService();
                    $notifier->sendOrderStatusUpdate($data['order_id'], 'scheduled');
                    break;
                    
                case 'order.update':
                    $order = new \Models\Order();
                    $order->updateStatus($data);
                    break;
                    
                default:
                    throw new \Exception('Unknown webhook event', self::HTTP_BAD_REQUEST);
            }
            return $this->jsonResponse(['success' => true]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], $e->getCode() ?: self::HTTP_BAD_REQUEST);
        }
    }

    public function getProducts() {
        $product = new \Models\Product();
        $products = $product->getAllActive();
        
        return $this->jsonResponse([
            'success' => true,
            'products' => $products
        ]);
    }

    public function calculatePrice() {
        $this->validateWebhookPayload();
        
        $calculator = new \Services\PriceCalculator();
        $price = $calculator->calculate(
            $this->request->getParam('product_id'),
            $this->request->getParam('quantity'),
            $this->request->getParam('options')
        );
        
        return $this->jsonResponse([
            'success' => true,
            'price' => $price,
            'breakdown' => $calculator->getPriceBreakdown()
        ]);
    }

    public function createQuote() {
        $quote = new \Models\Quote();
        $quoteData = $this->request->getParam('quote_data');
        
        $result = $quote->create($quoteData);
        
        return $this->jsonResponse([
            'success' => true,
            'quote_id' => $result->id,
            'total' => $result->total
        ]);
    }
}