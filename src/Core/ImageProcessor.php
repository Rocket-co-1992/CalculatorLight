<?php
namespace Core;

class ImageProcessor {
    public function resize($imagePath, $width, $height, $maintainAspectRatio = true) {
        $image = imagecreatefromstring(file_get_contents($imagePath));
        $resized = imagecreatetruecolor($width, $height);
        
        if ($maintainAspectRatio) {
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = $originalWidth * $ratio;
            $newHeight = $originalHeight * $ratio;
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        }
        
        return $resized;
    }

    public function applyFilter($image, $filterType) {
        switch ($filterType) {
            case 'grayscale':
                imagefilter($image, IMG_FILTER_GRAYSCALE);
                break;
            case 'brightness':
                imagefilter($image, IMG_FILTER_BRIGHTNESS, 20);
                break;
            case 'contrast':
                imagefilter($image, IMG_FILTER_CONTRAST, -20);
                break;
        }
        return $image;
    }

    public function renderDesign($elements) {
        $canvas = imagecreatetruecolor(800, 600);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));

        foreach ($elements as $element) {
            switch ($element['type']) {
                case 'text':
                    $this->renderText($canvas, $element);
                    break;
                case 'image':
                    $this->renderImage($canvas, $element);
                    break;
                case 'shape':
                    $this->renderShape($canvas, $element);
                    break;
            }
        }

        return $canvas;
    }

    private function renderText($canvas, $element) {
        $color = imagecolorallocate($canvas, 0, 0, 0);
        imagettftext(
            $canvas,
            $element['fontSize'],
            $element['rotation'] ?? 0,
            $element['position']['x'],
            $element['position']['y'],
            $color,
            $element['fontFile'],
            $element['text']
        );
    }
}
