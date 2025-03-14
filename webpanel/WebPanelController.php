<?php
namespace WebPanel;

class WebPanelController {
    private $auth;
    private $monitor;
    
    public function __construct() {
        $this->auth = new Auth();
        $this->monitor = new SystemMonitor();
        $this->validateAccess();
    }
    
    public function dashboard() {
        $stats = $this->monitor->getSystemStats();
        $optimizer = new Optimizer();
        
        return [
            'template' => 'webpanel/dashboard.twig',
            'data' => [
                'system_health' => $stats,
                'optimization_status' => $optimizer->getStatus(),
                'recent_activity' => $this->getRecentActivity()
            ]
        ];
    }

    private function validateAccess() {
        if (!isset($_SESSION['admin_user'])) {
            header('Location: /webpanel/login');
            exit;
        }
    }
}
