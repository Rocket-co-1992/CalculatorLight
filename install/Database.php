<?php
namespace Install;

class Database {
    private $config;
    private $connection;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function connect() {
        try {
            $this->connection = new \PDO(
                "mysql:host={$this->config['host']};charset=utf8mb4",
                $this->config['user'],
                $this->config['pass']
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function createDatabase() {
        try {
            $this->connection->exec("CREATE DATABASE IF NOT EXISTS {$this->config['name']}");
            $this->connection->exec("USE {$this->config['name']}");
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
