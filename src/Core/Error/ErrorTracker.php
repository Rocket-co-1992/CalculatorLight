<?php
namespace Core\Error;

class ErrorTracker {
    private $db;
    private $logger;
    private $notifier;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->logger = new \Core\Logger();
        $this->notifier = new \Core\Notification\NotificationManager();
    }

    public function trackError($error, $context = []) {
        $errorId = $this->logError($error, $context);
        $this->analyzeError($errorId);
        
        if ($this->isProductionImpacting($error)) {
            $this->notifyTeam($error);
        }
        
        return $errorId;
    }

    private function logError($error, $context) {
        $sql = "INSERT INTO error_log (error_message, error_code, stack_trace, context) 
                VALUES (:message, :code, :trace, :context)";
        
        $this->db->prepare($sql)->execute([
            'message' => $error->getMessage(),
            'code' => $error->getCode(),
            'trace' => $error->getTraceAsString(),
            'context' => json_encode($context)
        ]);
        
        return $this->db->lastInsertId();
    }
}
