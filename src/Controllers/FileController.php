<?php
namespace Controllers;

use Core\Request;
use Core\FileSystem\FileManager;

class FileController {
    private $fileManager;
    private $request;

    public function __construct(Request $request) {
        $this->fileManager = new FileManager();
        $this->request = $request;
    }

    public function upload() {
        if (!$this->request->isAjax()) {
            return ['error' => 'Invalid request'];
        }

        $files = $this->request->getFiles('files');
        $response = $this->fileManager->processFiles($files);
        
        return [
            'success' => true,
            'files' => $response
        ];
    }
}
