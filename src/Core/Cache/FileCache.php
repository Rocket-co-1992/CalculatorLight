<?php
namespace Core\Cache;

class FileCache {
    private $cachePath;
    private $maxAge;

    public function __construct($maxAge = 86400) {
        $this->cachePath = __DIR__ . '/../../../storage/cache';
        $this->maxAge = $maxAge;
        $this->ensureCacheDirectory();
    }

    public function store($key, $content, $metadata = []) {
        $cacheFile = $this->getCacheFilePath($key);
        $data = [
            'content' => $content,
            'metadata' => $metadata,
            'created_at' => time()
        ];
        return file_put_contents($cacheFile, json_encode($data));
    }

    public function get($key) {
        $cacheFile = $this->getCacheFilePath($key);
        if ($this->isValid($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true);
            return $data['content'];
        }
        return null;
    }

    private function isValid($cacheFile) {
        if (!file_exists($cacheFile)) {
            return false;
        }
        $data = json_decode(file_get_contents($cacheFile), true);
        return (time() - $data['created_at']) < $this->maxAge;
    }
}
