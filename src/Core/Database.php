<?php
namespace Core;

class Database {
    private static $instance = null;
    private $connection = null;
    private $retryAttempts = 3;
    private $retryDelay = 2;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->connection === null) {
            $attempts = 0;
            while ($attempts < $this->retryAttempts) {
                try {
                    $this->connection = new \PDO(
                        "mysql:host=" . getenv('DB_HOST') . 
                        ";dbname=" . getenv('DB_NAME') . 
                        ";charset=utf8mb4",
                        getenv('DB_USER'),
                        getenv('DB_PASS'),
                        [
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                            \PDO::ATTR_PERSISTENT => false,
                            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                        ]
                    );
                    break;
                } catch (\PDOException $e) {
                    $attempts++;
                    if ($attempts >= $this->retryAttempts) {
                        $this->handleError($e);
                    }
                    sleep($this->retryDelay);
                }
            }
        }
        return $this->connection;
    }

    private function handleError(\PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        if (getenv('APP_DEBUG') === 'true') {
            throw $e;
        }
        throw new \Exception("Database connection error");
    }
}
