<?php
namespace WebPanel;

class DatabaseCache {
    private $db;
    private $prefix;
    
    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->prefix = \Core\Config::get('database.prefix', 'wtp_');
    }
    
    public function remember($key, $ttl, $callback) {
        $value = $this->get($key);
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        return $value;
    }
    
    public function tags(array $tags) {
        return new TaggedCache($this, $tags);
    }
}
