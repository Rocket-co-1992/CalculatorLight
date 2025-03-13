<?php
namespace Controllers;

use Core\Request;
use Core\FileSystem\FileProcessor;
use Core\Response\JsonResponse;
use Core\Validation\FileValidator;
use Core\Exceptions\ValidationException;
use Core\Exceptions\ProcessingException;
use Core\Log\Log;

class FileUploadController {
    private const MAX_FILE_SIZE = 50 * 1024 * 1024; // 50MB
    private const ALLOWED_TYPES = [
        'image/jpeg', 'image/png', 'application/pdf',
        'application/postscript', 'image/vnd.adobe.photoshop'
    ];

    private $fileProcessor;
    private $request;

    public function __construct(Request $request) {
        $this->fileProcessor = new FileProcessor();
        $this->request = $request;
    }

    public function upload() {
        try {
            $files = $this->request->getFiles('files');
            
            $validator = new FileValidator(self::ALLOWED_TYPES, self::MAX_FILE_SIZE);
            $validationResult = $validator->validate($files);

            if (!$validationResult->isValid()) {
                throw new ValidationException($validationResult->getErrors());
            }

            $processedFiles = $this->fileProcessor->processWithRetry($files);
            
            return new JsonResponse([
                'success' => true,
                'files' => $processedFiles
            ]);
        } catch (ValidationException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (ProcessingException $e) {
            Log::error('File processing failed', ['error' => $e->getMessage()]);
            return new JsonResponse(['error' => 'File processing failed'], 500);
        }
    }
}
