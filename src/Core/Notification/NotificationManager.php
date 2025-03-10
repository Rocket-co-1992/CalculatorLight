<?php
namespace Core\Notification;

class NotificationManager {
    private $db;
    private $mailer;
    private $wsServer;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->mailer = new \Core\Mailer();
        $this->wsServer = new \Core\WebSocket\Server();
    }

    public function sendJobNotification($jobId, $status) {
        $notification = $this->createNotification($jobId, 'job_status', [
            'status' => $status,
            'timestamp' => time()
        ]);

        $this->wsServer->broadcast([
            'type' => 'job_update',
            'data' => $notification
        ]);

        return $notification['id'];
    }

    private function createNotification($userId, $type, $data) {
        $sql = "INSERT INTO notifications (user_id, type, message, data) 
                VALUES (:user_id, :type, :message, :data)";
                
        $this->db->prepare($sql)->execute([
            'user_id' => $userId,
            'type' => $type,
            'message' => $this->formatMessage($type, $data),
            'data' => json_encode($data)
        ]);
    }
}
