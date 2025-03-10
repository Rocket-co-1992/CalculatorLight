<?php
namespace Core\Backup;

class BackupManager {
    private $db;
    private $config;
    private $logger;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->logger = new \Core\Logger();
    }

    public function createBackup($type = 'full') {
        $backupPath = $this->getBackupPath();
        $timestamp = date('Y-m-d_H-i-s');
        
        switch ($type) {
            case 'full':
                return $this->createFullBackup($backupPath, $timestamp);
            case 'designs':
                return $this->backupDesigns($backupPath, $timestamp);
            case 'orders':
                return $this->backupOrders($backupPath, $timestamp);
        }
    }

    private function createFullBackup($path, $timestamp) {
        $filename = "backup_full_{$timestamp}.zip";
        $files = array_merge(
            $this->getDatabaseDump(),
            $this->getDesignFiles(),
            $this->getUploadedFiles()
        );
        
        return $this->createArchive($path . $filename, $files);
    }
}
