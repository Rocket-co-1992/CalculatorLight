CREATE TABLE system_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_name VARCHAR(50) NOT NULL,
    metric_value DECIMAL(10,2) NOT NULL,
    metric_type ENUM('performance', 'usage', 'error', 'custom') NOT NULL,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE metric_aggregates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric_name VARCHAR(50) NOT NULL,
    period_type ENUM('hourly', 'daily', 'weekly', 'monthly') NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NOT NULL,
    min_value DECIMAL(10,2),
    max_value DECIMAL(10,2),
    avg_value DECIMAL(10,2),
    count INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metric_period (metric_name, period_type, start_time)
);

CREATE INDEX idx_metrics_name_time ON system_metrics(metric_name, created_at);
