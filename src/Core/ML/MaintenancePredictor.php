<?php
namespace Core\ML;

class MaintenancePredictor {
    private $db;
    private $modelCache;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->modelCache = new \Core\Cache\FileCache();
    }

    public function predictNextMaintenance($printerId) {
        $trainingData = $this->getHistoricalData($printerId);
        $model = $this->loadOrTrainModel($trainingData);
        
        return [
            'next_maintenance' => $this->predictDate($model, $printerId),
            'confidence' => $this->calculateConfidence($model),
            'risk_factors' => $this->identifyRiskFactors($printerId)
        ];
    }

    private function loadOrTrainModel($data) {
        $cacheKey = 'ml_model_maintenance_' . md5(serialize($data));
        if ($cached = $this->modelCache->get($cacheKey)) {
            return $cached;
        }

        $model = $this->trainModel($data);
        $this->modelCache->store($cacheKey, $model);
        return $model;
    }
}
