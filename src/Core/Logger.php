<?php
namespace Core;

class Logger {
    private $db;
    private $config;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../config/config.php';
    }

    public function logActivity($userId, $action, $data = []) {
        $sql = "INSERT INTO user_activity_log (user_id, action, data) VALUES (?, ?, ?)";
        return $this->db->prepare($sql)->execute([
            $userId,
            $action,
            json_encode($data)
        ]);
    }

    public function logError($message, $context = []) {
        $errorData = [
            'message' => $message,
            'context' => $context,
            'timestamp' => date('Y-m-d H:i:s'),
            'severity' => $context['code'] ?? 'ERROR'
        ];

        if ($this->shouldNotifyAdmin($context)) {
            $this->notifyAdmin($errorData);
        }

        return $this->db->prepare("
            INSERT INTO error_log (error_message, error_code, context, created_at)
            VALUES (:message, :code, :context, NOW())"
        )->execute([
            'message' => $message,
            'code' => $context['code'] ?? 0,
            'context' => json_encode($context)
        ]);
    }

    private function shouldNotifyAdmin($context) {
        return isset($context['code']) && $context['code'] >= E_ERROR;
    }

    private function notifyAdmin($errorData) {
        $mailer = new Mailer();
        $mailer->sendErrorNotification($errorData);
    }
}
