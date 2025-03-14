<?php
echo "Initializing database...\n";

try {
    require_once __DIR__ . '/../bootstrap/app.php';
    $db = \Core\Database::getInstance()->getConnection();
    
    // Get all migration files
    $migrations = glob(__DIR__ . '/../database/migrations/*.sql');
    sort($migrations); // Ensure order by filename
    
    foreach ($migrations as $migration) {
        echo "Running migration: " . basename($migration) . "\n";
        $sql = file_get_contents($migration);
        
        // Split into individual statements
        $statements = array_filter(
            array_map('trim', 
                explode(';', $sql)
            )
        );
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $db->exec($statement);
            }
        }
    }
    
    echo "Database initialization completed successfully!\n";
    
} catch (Exception $e) {
    die("Database initialization failed: " . $e->getMessage() . "\n");
}
