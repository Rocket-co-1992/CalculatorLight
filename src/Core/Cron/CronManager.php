<?php
namespace Core\Cron;

class CronManager {
    private $db;
    private $logger;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->logger = new \Core\Logger();
    }

    public function runScheduledTasks() {
        $tasks = $this->getPendingTasks();
        foreach ($tasks as $task) {
            $this->executeTask($task);
        }
    }

    private function executeTask($task) {
        try {
            $handler = new $task['handler_class'];
            $result = $handler->execute($task['params']);
            
            $this->logTaskExecution($task['id'], $result);
        } catch (\Exception $e) {
            $this->logger->logError($e, [
                'task_id' => $task['id'],
                'context' => 'cron_execution'
            ]);
        }
    }
}
