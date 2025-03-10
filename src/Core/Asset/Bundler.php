<?php
namespace Core\Asset;

class Bundler {
    private $config;
    private $cache;

    public function __construct() {
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->cache = new \Core\Cache\FileCache();
    }

    public function bundle($type) {
        $cacheKey = "assets_{$type}_" . $this->getAssetsHash($type);
        
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $content = $this->processAssets($type);
        $this->cache->store($cacheKey, $content);
        
        return $content;
    }

    private function processAssets($type) {
        $files = $this->getAssetFiles($type);
        $content = '';
        
        foreach ($files as $file) {
            $content .= file_get_contents($file) . "\n";
        }

        return $type === 'js' ? $this->minifyJS($content) : $this->minifyCSS($content);
    }
}
