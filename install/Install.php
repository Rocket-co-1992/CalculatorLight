<?php
namespace Install;

class Install {
    private $dbConnection = null;
    private $config = [];
    private $errors = [];

    public function checkRequirements() {
        $requirements = [
            'PHP Version >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
            'PDO Extension' => extension_loaded('pdo'),
            'MySQL Extension' => extension_loaded('pdo_mysql'),
            'GD Library' => extension_loaded('gd'),
            'Zip Extension' => extension_loaded('zip'),
            'XML Extension' => extension_loaded('xml'),
            'Writable Storage' => is_writable('../storage'),
            'Writable Public' => is_writable('../public')
        ];

        foreach ($requirements as $requirement => $satisfied) {
            if (!$satisfied) {
                $this->errors[] = "Requirement not met: {$requirement}";
            }
        }

        return empty($this->errors);
    }

    public function createDatabaseTables() {
        $migrations = glob('../database/migrations/*.sql');
        sort($migrations);

        foreach ($migrations as $migration) {
            $sql = file_get_contents($migration);
            try {
                $this->dbConnection->exec($sql);
            } catch (\PDOException $e) {
                $this->errors[] = "Migration failed: " . $e->getMessage();
                return false;
            }
        }

        return true;
    }
}
