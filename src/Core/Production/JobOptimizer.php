<?php
namespace Core\Production;

class JobOptimizer {
    private $db;
    private $cache;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->cache = new \Core\Cache\FileCache();
    }

    public function optimizeBatch($batchId) {
        $cacheKey = "batch_optimization_{$batchId}";
        
        if ($cached = $this->cache->get($cacheKey)) {
            return $cached;
        }

        $optimization = $this->calculateOptimization($batchId);
        $this->cache->store($cacheKey, $optimization);
        
        return $optimization;
    }

    private function calculateOptimization($batchId) {
        $batch = $this->getBatchItems($batchId);
        return [
            'sheet_layout' => $this->calculateSheetLayout($batch),
            'ink_usage' => $this->estimateInkUsage($batch),
            'material_saving' => $this->calculateMaterialSaving($batch)
        ];
    }

    private function getBatchItems($batchId) {
        return $this->db->prepare(
            "SELECT * FROM batch_items WHERE batch_id = ?"
        )->execute([$batchId])->fetchAll();
    }
}
