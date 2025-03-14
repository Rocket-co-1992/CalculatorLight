<?php
namespace WebPanel;

class Security {
    private $config;

    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
    }

    public function enforceSSL() {
        if ($this->config['security']['force_ssl'] && 
            empty($_SERVER['HTTPS'])) {
            header('Location: https://' . $_SERVER['HTTP_HOST'] . 
                   $_SERVER['REQUEST_URI']);
            exit;
        }
    }

    public function validateIP() {
        $allowed = $this->config['security']['allowed_ips'];
        if (!empty($allowed) && 
            !in_array($_SERVER['REMOTE_ADDR'], $allowed)) {
            http_response_code(403);
            exit('Access denied');
        }
    }

    public function scanUploads($file) {
        // Implement virus scanning for uploads
        $mimeType = mime_content_type($file);
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        
        if (!in_array($mimeType, $allowedTypes)) {
            throw new \Exception('Invalid file type');
        }
        
        // Additional security checks
        return true;
    }
}
