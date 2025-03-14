<?php
namespace WebPanel;

class Deployment {
    private $rootPath;
    private $backupPath;

    public function __construct() {
        $this->rootPath = dirname(__DIR__);
        $this->backupPath = $this->rootPath . '/storage/backups';
    }

    public function deploy() {
        try {
            // Create backup
            $this->createBackup();

            // Run migrations
            $this->runMigrations();

            // Clear caches
            $this->clearCaches();

            // Update permissions
            $this->updatePermissions();

            return ['success' => true, 'message' => 'Deployment completed successfully'];
        } catch (\Exception $e) {
            $this->logError('Deployment failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function createBackup() {
        $timestamp = date('Y-m-d_H-i-s');
        $backupFile = "{$this->backupPath}/backup_{$timestamp}.zip";

        $zip = new \ZipArchive();
        if ($zip->open($backupFile, \ZipArchive::CREATE)) {
            $this->addDirectoryToZip($zip, $this->rootPath . '/public');
            $this->addDirectoryToZip($zip, $this->rootPath . '/src');
            $zip->close();
        }
    }

    private function updatePermissions() {
        $permissions = [
            'storage' => 0755,
            'public' => 0755,
            'public/uploads' => 0755,
            '.env' => 0644,
            'public/.htaccess' => 0644
        ];

        foreach ($permissions as $path => $mode) {
            chmod($this->rootPath . '/' . $path, $mode);
        }
    }
}
