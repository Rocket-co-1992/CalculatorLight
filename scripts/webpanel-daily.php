<?php
require_once __DIR__ . '/../bootstrap/app.php';

echo "Starting daily WebPanel maintenance...\n";

try {
    // Run maintenance tasks
    $maintenance = new \WebPanel\Maintenance();
    $maintenance->runMaintenance();
    
    // Process print queue
    $queue = new \WebPanel\Queue();
    $queue->processQueue();
    
    // Generate reports
    $reporting = new \Services\ReportGenerator();
    $reporting->generateDailyReports();
    
    // Update statistics
    $stats = new \Services\Analytics();
    $stats->updateDailyStats();
    
    echo "Daily maintenance completed successfully.\n";
} catch (\Exception $e) {
    echo "Error during maintenance: " . $e->getMessage() . "\n";
    exit(1);
}
