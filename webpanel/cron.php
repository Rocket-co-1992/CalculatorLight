<?php
namespace WebPanel;

class CronManager {
    private $db;
    private $logPath;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->logPath = dirname(__DIR__) . '/storage/logs/cron.log';
    }

    public function runScheduledTasks() {
        $this->cleanTempFiles();
        $this->checkPendingOrders();
        $this->updateStockLevels();
        $this->generateDailyReports();
        $this->checkSSLExpiry();
    }

    private function cleanTempFiles() {
        $tempDir = dirname(__DIR__) . '/public/temp';
        $expiry = strtotime('-24 hours');

        if ($handle = opendir($tempDir)) {
            while (false !== ($file = readdir($handle))) {
                $filePath = $tempDir . '/' . $file;
                if (is_file($filePath) && filemtime($filePath) < $expiry) {
                    unlink($filePath);
                }
            }
            closedir($handle);
        }
    }

    private function generateDailyReports() {
        $reports = [
            'sales' => new \Services\ReportGenerator('sales'),
            'production' => new \Services\ReportGenerator('production'),
            'inventory' => new \Services\ReportGenerator('inventory')
        ];

        foreach ($reports as $type => $generator) {
            $generator->generateDaily();
        }
    }
}
