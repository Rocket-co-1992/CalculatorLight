<?php
namespace WebPanel;

class Backup {
    private $config;
    private $backupPath;

    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
        $this->backupPath = dirname(__DIR__) . '/storage/backups';
    }

    public function run() {
        $timestamp = date('Y-m-d_H-i-s');
        
        try {
            // Database backup
            $this->backupDatabase($timestamp);
            
            // Files backup
            $this->backupFiles($timestamp);
            
            // Cleanup old backups
            $this->cleanupOldBackups();
            
            return ['success' => true, 'timestamp' => $timestamp];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function backupDatabase($timestamp) {
        $filename = "db_backup_{$timestamp}.sql";
        $path = "{$this->backupPath}/database/{$filename}";
        
        $command = sprintf(
            "mysqldump --host=%s --user=%s --password=%s %s > %s",
            escapeshellarg($this->config['database']['host']),
            escapeshellarg($this->config['database']['user']),
            escapeshellarg($this->config['database']['password']),
            escapeshellarg($this->config['database']['name']),
            escapeshellarg($path)
        );
        
        exec($command);
    }
}
