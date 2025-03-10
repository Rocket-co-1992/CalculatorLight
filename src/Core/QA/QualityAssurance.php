<?php
namespace Core\QA;

class QualityAssurance {
    private $db;
    private $logger;
    private $config;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->logger = new \Core\Logger();
        $this->config = require __DIR__ . '/../../../config/config.php';
    }

    public function runQualityChecks($orderId) {
        return [
            'design' => $this->checkDesignQuality($orderId),
            'prepress' => $this->checkPrepressQuality($orderId),
            'materials' => $this->checkMaterialsQuality($orderId),
            'colorProfile' => $this->checkColorProfile($orderId)
        ];
    }

    public function generateQAReport($checkResults) {
        $report = [
            'timestamp' => time(),
            'results' => $checkResults,
            'recommendations' => $this->generateRecommendations($checkResults),
            'overall_score' => $this->calculateOverallScore($checkResults)
        ];

        $this->saveQAReport($report);
        return $report;
    }
}
