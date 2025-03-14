<?php
namespace WebPanel;

class SystemMonitor {
    private $db;
    private $logPath;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->logPath = dirname(__DIR__) . '/storage/logs/system.log';
    }

    public function checkSystemHealth() {
        return [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorageSpace(),
            'memory' => $this->checkMemoryUsage(),
            'queue' => $this->checkPrintQueue(),
            'ssl' => $this->checkSSLCertificate()
        ];
    }

    private function checkStorageSpace() {
        $directories = ['storage', 'public/uploads'];
        $results = [];

        foreach ($directories as $dir) {
            $total = disk_total_space($dir);
            $free = disk_free_space($dir);
            $used = $total - $free;
            $percentage = ($used / $total) * 100;

            if ($percentage > 90) {
                $this->logWarning("Storage space critical in {$dir}: {$percentage}% used");
            }

            $results[$dir] = [
                'total' => $total,
                'free' => $free,
                'used_percentage' => $percentage
            ];
        }

        return $results;
    }
}
