<?php
namespace WebPanel;

class WebPanelManager {
    private $config;
    private $logger;
    
    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
        $this->logger = new Logger();
    }
    
    public function initialize() {
        $this->checkEnvironment();
        $this->configurePaths();
        $this->setupDatabases();
        $this->initializeCache();
        return $this->verifyInstallation();
    }
    
    private function checkEnvironment() {
        $monitor = new SystemMonitor();
        $status = $monitor->checkSystemHealth();
        
        foreach ($status as $check => $result) {
            if ($result['status'] === 'critical') {
                throw new \Exception("Critical check failed: {$check}");
            }
        }
    }
    
    private function verifyInstallation() {
        $requiredFiles = [
            'public/index.php',
            'storage/logs',
            'storage/cache',
            'config/webpanel.php'
        ];
        
        foreach ($requiredFiles as $file) {
            if (!file_exists(dirname(__DIR__) . '/' . $file)) {
                throw new \Exception("Missing required file: {$file}");
            }
        }
        
        return true;
    }
}
