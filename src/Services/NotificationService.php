<?php
namespace Services;

class NotificationService {
    private $db;
    private $mailer;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->mailer = new \Core\Mailer();
    }
    
    public function sendOrderStatusUpdate($orderId, $status) {
        $order = new \Models\Order();
        $orderData = $order->getById($orderId);
        
        $template = $this->getTemplateForStatus($status);
        $this->mailer->send([
            'to' => $orderData['customer_email'],
            'subject' => "Order #{$orderId} Status Update",
            'template' => $template,
            'data' => $orderData
        ]);
        
        $this->logNotification($orderId, 'order_status', $status);
    }
    
    public function sendLowStockAlert($materialId) {
        $inventory = new \Models\Inventory();
        $material = $inventory->getMaterialDetails($materialId);
        
        $this->mailer->send([
            'to' => $this->getAdminEmails(),
            'subject' => "Low Stock Alert - {$material['name']}",
            'template' => 'notifications/low_stock.twig',
            'data' => $material
        ]);
    }
    
    private function logNotification($entityId, $type, $content) {
        $sql = "INSERT INTO notification_log (
            entity_id, type, content, created_at
        ) VALUES (
            :entity_id, :type, :content, NOW()
        )";
        
        return $this->db->prepare($sql)->execute([
            'entity_id' => $entityId,
            'type' => $type,
            'content' => $content
        ]);
    }
}
