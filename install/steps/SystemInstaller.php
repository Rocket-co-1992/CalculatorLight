<?php
namespace Install\Steps;

class SystemInstaller {
    private $errors = [];
    private $baseDir;

    public function __construct() {
        $this->baseDir = dirname(dirname(__DIR__));
    }

    public function install() {
        try {
            $this->createDirectoryStructure();
            $this->setupDatabase();
            $this->configureEnvironment();
            $this->setupCacheSystem();
            $this->setupPermissions();
            
            return ['success' => true];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function createDirectoryStructure() {
        $directories = [
            'storage/app/public',
            'storage/cache',
            'storage/logs',
            'storage/framework/sessions',
            'storage/framework/views',
            'public/uploads/designs',
            'public/uploads/products',
            'public/temp'
        ];

        foreach ($directories as $dir) {
            $path = $this->baseDir . '/' . $dir;
            if (!file_exists($path)) {
                if (!mkdir($path, 0755, true)) {
                    throw new \Exception("Could not create directory: {$dir}");
                }
            }
        }
    }
}
