CREATE TABLE system_health_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    check_type VARCHAR(50) NOT NULL,
    status ENUM('ok', 'warning', 'error') NOT NULL,
    details JSON,
    checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE performance_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_name VARCHAR(50) NOT NULL,
    metric_value DECIMAL(10,2) NOT NULL,
    context JSON,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metric_time (metric_name, recorded_at)
);

CREATE TABLE error_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    error_type VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    stack_trace TEXT,
    context JSON,
    occurrences INT DEFAULT 1,
    first_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
