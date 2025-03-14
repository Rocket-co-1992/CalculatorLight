<?php
namespace WebPanel;

class Maintenance {
    private $db;
    private $config;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
    }
    
    public function runMaintenance() {
        // Enable maintenance mode
        file_put_contents(
            dirname(__DIR__) . '/public/maintenance.flag',
            time()
        );
        
        try {
            $this->cleanOldRecords();
            $this->optimizeStorage();
            $this->validateFiles();
            $this->optimizeCache();
            
            // Run optimizer
            $optimizer = new Optimizer();
            $optimizer->optimize();
            
        } finally {
            // Disable maintenance mode
            @unlink(dirname(__DIR__) . '/public/maintenance.flag');
        }
    }
    
    private function cleanOldRecords() {
        $queries = [
            "DELETE FROM analytics_events WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)",
            "DELETE FROM temporary_files WHERE created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)",
            "DELETE FROM failed_jobs WHERE failed_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        ];
        
        foreach ($queries as $query) {
            $this->db->exec($query);
        }
    }
    
    private function optimizeCache() {
        $cache = new DatabaseCache();
        
        // Clear old cache entries
        $this->db->exec("DELETE FROM cache WHERE expiration < UNIX_TIMESTAMP()");
        
        // Reset cache statistics
        $this->db->exec("ANALYZE TABLE cache");
        
        // Warm up frequently accessed cache
        $this->warmupCache();
    }
    
    private function warmupCache() {
        $frequentProducts = $this->db->query(
            "SELECT id FROM products WHERE active = 1 ORDER BY views DESC LIMIT 10"
        )->fetchAll();
        
        foreach ($frequentProducts as $product) {
            $this->cacheProductData($product['id']);
        }
    }
}
