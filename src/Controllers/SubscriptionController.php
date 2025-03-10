<?php
namespace Controllers;

use Core\Request;
use Core\Billing\SubscriptionManager;

class SubscriptionController {
    private $request;
    private $subscriptionManager;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->subscriptionManager = new SubscriptionManager();
    }

    public function subscribe() {
        $planId = $this->request->getParam('plan_id');
        $paymentMethod = $this->request->getParam('payment_method');
        
        try {
            $subscription = $this->subscriptionManager->createSubscription(
                $_SESSION['user_id'],
                $planId,
                $paymentMethod
            );
            
            return [
                'success' => true,
                'subscription_id' => $subscription['id']
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
