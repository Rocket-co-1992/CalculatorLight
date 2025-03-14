<?php
namespace WebPanel;

class Environment {
    public static function detect() {
        return [
            'server' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
                'php_version' => PHP_VERSION,
                'document_root' => $_SERVER['DOCUMENT_ROOT'],
                'ssl' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
            ],
            'paths' => [
                'base' => dirname(__DIR__),
                'storage' => dirname(__DIR__) . '/storage',
                'public' => dirname(__DIR__) . '/public',
                'temp' => dirname(__DIR__) . '/public/temp'
            ],
            'permissions' => self::checkPermissions()
        ];
    }

    private static function checkPermissions() {
        $paths = [
            'storage' => dirname(__DIR__) . '/storage',
            'public/uploads' => dirname(__DIR__) . '/public/uploads',
            'temp' => dirname(__DIR__) . '/public/temp'
        ];

        $results = [];
        foreach ($paths as $key => $path) {
            $results[$key] = [
                'exists' => file_exists($path),
                'writable' => is_writable($path),
                'mode' => fileperms($path) & 0777
            ];
        }
        return $results;
    }
}
