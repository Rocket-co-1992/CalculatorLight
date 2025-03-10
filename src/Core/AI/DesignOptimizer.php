<?php
namespace Core\AI;

class DesignOptimizer {
    private $db;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
    }

    public function suggestImprovements($designData) {
        $suggestions = [];
        
        // Analyze text readability
        if ($this->hasSmallText($designData)) {
            $suggestions[] = [
                'type' => 'readability',
                'message' => 'Consider increasing text size for better readability'
            ];
        }

        // Check color contrast
        $contrastIssues = $this->analyzeColorContrast($designData);
        if ($contrastIssues) {
            $suggestions[] = [
                'type' => 'contrast',
                'message' => 'Improve color contrast for better visibility'
            ];
        }

        return $suggestions;
    }

    public function optimizeForPrinting($designData) {
        return [
            'color_mode' => 'CMYK',
            'resolution' => $this->calculateOptimalDPI($designData),
            'bleed' => $this->calculateBleedArea($designData)
        ];
    }

    private function hasSmallText($designData) {
        foreach ($designData['elements'] as $element) {
            if ($element['type'] === 'text' && $element['fontSize'] < 8) {
                return true;
            }
        }
        return false;
    }

    private function calculateOptimalDPI($designData) {
        // Calculate based on final print size and viewing distance
        return 300; // Default commercial print standard
    }
}
