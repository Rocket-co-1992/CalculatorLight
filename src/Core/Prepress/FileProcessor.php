<?php
namespace Core\Prepress;

class FileProcessor {
    private $config;
    private $supportedFormats = ['pdf', 'ai', 'eps', 'psd'];
    private $optimizer;

    public function __construct() {
        $this->config = require __DIR__ . '/../../../config/config.php';
        $this->optimizer = new FileOptimizer();
    }

    public function processFile($filePath, $options = []) {
        $fileInfo = $this->validateFile($filePath);
        
        if (!$fileInfo['valid']) {
            throw new \Exception('Invalid file format');
        }

        // Add optimization step
        $optimizedFile = $this->optimizer->optimizeFile($filePath, $options);

        return [
            'colorSpace' => $this->checkColorSpace($optimizedFile),
            'resolution' => $this->checkResolution($optimizedFile),
            'bleed' => $this->checkBleedArea($optimizedFile),
            'fonts' => $this->validateFonts($optimizedFile),
            'optimizedPath' => $optimizedFile
        ];
    }

    private function validateFile($filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return [
            'valid' => in_array($extension, $this->supportedFormats),
            'format' => $extension
        ];
    }
}
