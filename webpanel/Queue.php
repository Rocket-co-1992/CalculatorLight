<?php
namespace WebPanel;

class Queue {
    private $db;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }
    
    public function processQueue() {
        $sql = "SELECT * FROM print_jobs 
                WHERE status = 'pending' 
                ORDER BY priority DESC, created_at ASC 
                LIMIT 10";
                
        $jobs = $this->db->query($sql)->fetchAll();
        
        foreach ($jobs as $job) {
            $this->processJob($job);
            $this->updateJobStatus($job['id'], 'processing');
        }
    }
    
    private function processJob($job) {
        try {
            $processor = new JobProcessor($job);
            $result = $processor->execute();
            
            if ($result['success']) {
                $this->updateJobStatus($job['id'], 'completed');
            } else {
                throw new \Exception($result['error']);
            }
        } catch (\Exception $e) {
            $this->logJobError($job['id'], $e->getMessage());
        }
    }
}
