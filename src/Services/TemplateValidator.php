<?php
namespace Services;

class TemplateValidator {
    private $errors = [];
    
    public function validate($template) {
        $this->errors = [];
        
        $this->validateDimensions($template);
        $this->validateLayers($template);
        $this->validateBleed($template);
        $this->validateColorProfile($template);
        
        return empty($this->errors);
    }
    
    private function validateDimensions($template) {
        if (!isset($template['width']) || !isset($template['height'])) {
            $this->errors[] = 'Template dimensions are required';
            return;
        }
        
        if ($template['width'] < 1 || $template['height'] < 1) {
            $this->errors[] = 'Invalid template dimensions';
        }
    }
    
    private function validateLayers($template) {
        if (!isset($template['layers']) || !is_array($template['layers'])) {
            $this->errors[] = 'Template must contain at least one layer';
            return;
        }
        
        foreach ($template['layers'] as $layer) {
            if (!isset($layer['type']) || !isset($layer['content'])) {
                $this->errors[] = 'Invalid layer structure';
            }
        }
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
