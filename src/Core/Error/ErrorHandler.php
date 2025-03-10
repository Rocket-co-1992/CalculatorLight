<?php
namespace Core\Error;

class ErrorHandler {
    private $logger;
    private $debug;

    public function __construct() {
        $this->logger = new \Core\Logger();
        $this->debug = require(__DIR__ . '/../../../config/config.php')['app']['debug'];
        $this->register();
    }

    public function register() {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $this->logError($errstr, $errno, $errfile, $errline);
        return true;
    }

    private function logError($message, $code, $file, $line) {
        $context = [
            'code' => $code,
            'file' => $file,
            'line' => $line,
            'url' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];

        $this->logger->logError($message, $context);
    }
}
