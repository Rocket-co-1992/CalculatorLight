<?php
namespace WebPanel;

class Cache {
    private $path;
    private $enabled;
    private $ttl;

    public function __construct() {
        $config = require dirname(__DIR__) . '/config/webpanel.php';
        $this->path = dirname(__DIR__) . '/storage/cache';
        $this->enabled = $config['caching']['enabled'];
        $this->ttl = $config['caching']['ttl'];
    }

    public function get($key) {
        if (!$this->enabled) return null;
        
        $file = $this->path . '/' . md5($key);
        if (!file_exists($file)) return null;
        
        $data = unserialize(file_get_contents($file));
        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }
        
        return $data['value'];
    }

    public function set($key, $value, $ttl = null) {
        if (!$this->enabled) return false;
        
        $data = [
            'value' => $value,
            'expires' => time() + ($ttl ?? $this->ttl)
        ];
        
        return file_put_contents(
            $this->path . '/' . md5($key),
            serialize($data)
        );
    }
}
