<?php
namespace WebPanel;

class Database {
    private $db;
    private $backupPath;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->backupPath = dirname(__DIR__) . '/storage/backups/database';
    }

    public function backup() {
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        $filepath = $this->backupPath . '/' . $filename;

        exec("mysqldump --host={$this->config['host']} " .
             "--user={$this->config['username']} " .
             "--password={$this->config['password']} " .
             "{$this->config['database']} > {$filepath}");

        if (file_exists($filepath)) {
            $this->compressBackup($filepath);
            return true;
        }
        return false;
    }

    public function optimize() {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $this->db->query("OPTIMIZE TABLE {$table}");
        }
    }
}
