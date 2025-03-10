<?php
namespace Core;

class OrderTracker {
    private $db;
    private $mailer;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->mailer = new Mailer();
    }

    public function updateOrderStatus($orderId, $status, $notify = true) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['status' => $status, 'id' => $orderId]);

        if ($result && $notify) {
            $this->notifyStatusChange($orderId, $status);
        }
        return $result;
    }

    public function getOrderTimeline($orderId) {
        $sql = "SELECT * FROM order_status_history WHERE order_id = :order_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }

    private function notifyStatusChange($orderId, $status) {
        $order = $this->getOrderDetails($orderId);
        $this->mailer->sendOrderStatusUpdate($order['user_email'], $order, $status);
    }
}
