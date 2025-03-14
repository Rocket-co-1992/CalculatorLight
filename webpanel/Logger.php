<?php
namespace WebPanel;

class Logger {
    private $logPath;
    private $maxLogSize = 10485760; // 10MB

    public function __construct() {
        $this->logPath = dirname(__DIR__) . '/storage/logs';
    }

    public function log($message, $level = 'info') {
        $logFile = $this->logPath . "/{$level}.log";
        
        // Rotate log if too large
        if (file_exists($logFile) && 
            filesize($logFile) > $this->maxLogSize) {
            $this->rotateLog($logFile);
        }

        $entry = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message
        );

        file_put_contents($logFile, $entry, FILE_APPEND);
    }

    private function rotateLog($file) {
        $backup = $file . '.' . date('Y-m-d-H-i-s');
        rename($file, $backup);
        
        // Compress old log
        $zip = new \ZipArchive();
        $zip->open($backup . '.zip', \ZipArchive::CREATE);
        $zip->addFile($backup);
        $zip->close();
        
        unlink($backup);
    }
}
