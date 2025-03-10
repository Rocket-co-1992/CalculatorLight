<?php
namespace Models;

class Shipping {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function calculateShipping($orderId, $address) {
        $order = $this->getOrderDetails($orderId);
        $rates = [
            'standard' => $this->calculateStandardRate($order, $address),
            'express' => $this->calculateExpressRate($order, $address),
            'pickup' => 0
        ];
        return $rates;
    }

    public function createShipment($orderId, $method) {
        $tracking = uniqid('track_');
        $sql = "INSERT INTO shipments (order_id, method, tracking_number) VALUES (?, ?, ?)";
        $this->db->prepare($sql)->execute([$orderId, $method, $tracking]);
        return $tracking;
    }

    private function getOrderDetails($orderId) {
        $sql = "SELECT * FROM orders WHERE id = ?";
        return $this->db->prepare($sql)->execute([$orderId])->fetch();
    }
}
