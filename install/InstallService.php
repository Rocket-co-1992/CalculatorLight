<?php
namespace Install;

class InstallService {
    private $installer;
    private $db;
    
    public function __construct() {
        $this->installer = new Install();
        $this->db = new Database([]);
    }
    
    public function runInstallation($config) {
        try {
            // 1. Validate configuration
            $this->validateConfig($config);
            
            // 2. Create database connection
            $this->db->setConfig($config['database']);
            if (!$this->db->connect()) {
                throw new \Exception("Could not connect to database");
            }
            
            // 3. Run migrations
            if (!$this->installer->createDatabaseTables()) {
                throw new \Exception("Failed to create database tables");
            }
            
            // 4. Create configuration files
            $this->createConfigFiles($config);
            
            // 5. Set up directories and permissions
            $this->setupDirectories();
            
            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false, 
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function validateConfig($config) {
        $required = ['app_name', 'app_url', 'db_host', 'db_name', 'db_user'];
        foreach ($required as $field) {
            if (empty($config[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }
    
    private function createConfigFiles($config) {
        $template = file_get_contents(__DIR__ . '/config-template.php');
        foreach ($config as $key => $value) {
            $template = str_replace("{{{$key}}}", $value, $template);
        }
        file_put_contents(dirname(__DIR__) . '/.env', $template);
    }
}
