<?php
namespace Services;

class DesignRenderer {
    private $width = 800;
    private $height = 600;
    
    public function generatePreview($designData) {
        // Create image canvas
        $image = imagecreatetruecolor($this->width, $this->height);
        
        // Set background
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        // Render design elements
        foreach ($designData['elements'] as $element) {
            $this->renderElement($image, $element);
        }
        
        // Save preview
        $filename = 'preview_' . uniqid() . '.png';
        $filepath = dirname(__DIR__, 2) . '/public/previews/' . $filename;
        imagepng($image, $filepath);
        imagedestroy($image);
        
        return '/previews/' . $filename;
    }
    
    private function renderElement($image, $element) {
        switch ($element['type']) {
            case 'text':
                $this->renderText($image, $element);
                break;
            case 'image':
                $this->renderImage($image, $element);
                break;
            case 'shape':
                $this->renderShape($image, $element);
                break;
        }
    }
}
