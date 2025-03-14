<?php
namespace WebPanel;

class Optimizer {
    private $config;
    
    public function __construct() {
        $this->config = require dirname(__DIR__) . '/config/webpanel.php';
    }
    
    public function optimize() {
        $this->optimizeDatabase();
        $this->optimizeImages();
        $this->clearCache();
        $this->compressAssets();
    }
    
    public function optimizeSystem() {
        // Database optimization
        $this->optimizeTables();
        $this->cleanupCache();
        $this->compressImages();
        
        // File system optimization
        $this->cleanupTempFiles();
        $this->optimizeUploads();
    }
    
    private function optimizeDatabase() {
        $db = \Core\Database::getInstance()->getConnection();
        $tables = $db->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        
        foreach ($tables as $table) {
            $db->query("OPTIMIZE TABLE {$table}");
        }
    }
    
    private function optimizeTables() {
        $tables = $this->db->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($tables as $table) {
            $this->db->query("OPTIMIZE TABLE {$table}");
            $this->db->query("ANALYZE TABLE {$table}");
        }
    }
    
    private function cleanupCache() {
        // Remove expired cache entries
        $this->db->query("DELETE FROM cache WHERE expiration < ?", [time()]);
        $this->db->query("DELETE FROM cache_locks WHERE expiration < ?", [time()]);
    }
    
    private function optimizeImages() {
        $uploadsDir = dirname(__DIR__) . '/public/uploads';
        
        if ($handle = opendir($uploadsDir)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                    $this->compressImage($uploadsDir . '/' . $file);
                }
            }
            closedir($handle);
        }
    }
    
    private function compressImage($file) {
        $info = getimagesize($file);
        if (!$info) return;
        
        switch ($info[2]) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
                imagejpeg($image, $file, 85);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
                imagepng($image, $file, 8);
                break;
        }
        
        if (isset($image)) {
            imagedestroy($image);
        }
    }
}
