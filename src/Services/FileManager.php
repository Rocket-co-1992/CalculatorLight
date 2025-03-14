<?php
namespace Services;

class FileManager {
    private $uploadDir;
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    
    public function __construct() {
        $this->uploadDir = dirname(__DIR__, 2) . '/public/uploads/';
    }
    
    public function handleUpload($file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception('Invalid file upload');
        }
        
        if (!in_array($file['type'], $this->allowedTypes)) {
            throw new \Exception('Invalid file type');
        }
        
        $filename = $this->generateUniqueFilename($file['name']);
        $filepath = $this->uploadDir . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new \Exception('File upload failed');
        }
        
        return [
            'filename' => $filename,
            'url' => '/uploads/' . $filename,
            'type' => $file['type']
        ];
    }
    
    private function generateUniqueFilename($originalName) {
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . time() . '.' . $ext;
    }
    
    public function deleteFile($filename) {
        $filepath = $this->uploadDir . $filename;
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
