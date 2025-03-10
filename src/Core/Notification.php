<?php
namespace Core;

class Notification {
    private $db;
    private $mailer;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->mailer = new Mailer();
    }

    public function send($userId, $type, $message, $data = []) {
        $sql = "INSERT INTO notifications (user_id, type, message, data) 
                VALUES (:user_id, :type, :message, :data)";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'data' => json_encode($data)
        ]);
    }

    public function markAsRead($notificationId) {
        $sql = "UPDATE notifications SET read_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$notificationId]);
    }
}
