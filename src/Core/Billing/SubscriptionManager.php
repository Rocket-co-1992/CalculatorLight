<?php
namespace Core\Billing;

class SubscriptionManager {
    private $db;
    private $mailer;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->mailer = new \Core\Mailer();
    }

    public function createSubscription($userId, $planId, $paymentMethod) {
        try {
            $this->db->beginTransaction();
            
            $subscription = $this->insertSubscription($userId, $planId);
            $this->setupRecurringPayment($subscription['id'], $paymentMethod);
            
            $this->db->commit();
            return $subscription;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function processRenewal($subscriptionId) {
        $subscription = $this->getSubscription($subscriptionId);
        if ($this->shouldRenew($subscription)) {
            return $this->renewSubscription($subscription);
        }
        return false;
    }
}
