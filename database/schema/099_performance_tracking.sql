CREATE TABLE performance_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('machine', 'operator', 'process') NOT NULL,
    entity_id INT NOT NULL,
    metric_type VARCHAR(50) NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE efficiency_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_date DATE NOT NULL,
    total_orders INT NOT NULL,
    completed_orders INT NOT NULL,
    total_production_time INT,
    material_efficiency DECIMAL(5,2),
    labor_efficiency DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_performance_entity ON performance_metrics(entity_type, entity_id);
CREATE INDEX idx_efficiency_date ON efficiency_reports(report_date);
