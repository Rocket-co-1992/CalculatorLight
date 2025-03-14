<?php
namespace WebPanel;

class Performance {
    private $metrics = [];
    private $startTime;

    public function __construct() {
        $this->startTime = microtime(true);
    }

    public function monitor() {
        $this->metrics = [
            'memory_usage' => $this->getMemoryUsage(),
            'cpu_load' => $this->getCPULoad(),
            'response_time' => $this->getResponseTime(),
            'database_queries' => $this->getDatabaseMetrics(),
            'cache_hits' => $this->getCacheMetrics()
        ];

        $this->analyzePerformance();
        return $this->metrics;
    }

    private function getMemoryUsage() {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit')
        ];
    }

    private function analyzePerformance() {
        if ($this->metrics['response_time'] > 2.0) {
            $this->optimizePerformance();
        }
    }

    private function optimizePerformance() {
        $cache = new Cache();
        $cache->optimizeStore();
        
        $optimizer = new Optimizer();
        $optimizer->compressAssets();
    }
}
