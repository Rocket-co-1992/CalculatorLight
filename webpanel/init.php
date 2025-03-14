<?php
namespace WebPanel;

class Init {
    private $config;
    private $logger;

    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
        $this->logger = new Logger();
    }

    public function initialize() {
        try {
            // Check system requirements
            $this->checkRequirements();

            // Initialize components
            $this->initializeComponents();

            // Setup cron jobs
            $this->setupCronJobs();

            // Configure PHP settings
            $this->configurePhp();

            return ['success' => true, 'message' => 'WebPanel initialized successfully'];
        } catch (\Exception $e) {
            $this->logger->log($e->getMessage(), 'error');
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function initializeComponents() {
        // Initialize required services
        new Cache();
        new Security();
        new Performance();
        
        // Setup monitoring
        $monitor = new SystemMonitor();
        $monitor->startMonitoring();
    }

    private function configurePhp() {
        $settings = [
            'session.gc_maxlifetime' => 3600,
            'session.cookie_secure' => true,
            'session.cookie_httponly' => true,
            'display_errors' => false,
            'error_reporting' => E_ALL & ~E_DEPRECATED & ~E_STRICT
        ];

        foreach ($settings as $key => $value) {
            ini_set($key, $value);
        }
    }
}
