<?php
namespace Core\Asset;

class AssetManager {
    private $bundler;
    private $config;

    public function __construct() {
        $this->bundler = new Bundler();
        $this->config = require __DIR__ . '/../../../config/config.php';
    }

    public function registerAssets($type, $files) {
        if (!$this->config['app']['debug']) {
            return $this->getBundledAsset($type);
        }
        return $this->getIndividualAssets($type, $files);
    }

    private function getBundledAsset($type) {
        $bundle = $this->bundler->bundle($type);
        $hash = md5($bundle);
        
        file_put_contents(
            PUBLIC_PATH . "/assets/dist/{$type}/{$hash}.{$type}",
            $bundle
        );

        return ["/assets/dist/{$type}/{$hash}.{$type}"];
    }
}
