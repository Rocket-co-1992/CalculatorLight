CREATE TABLE machine_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    metric_type VARCHAR(50) NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE TABLE maintenance_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    maintenance_type VARCHAR(50) NOT NULL,
    scheduled_date DATE NOT NULL,
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    technician_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);

CREATE INDEX idx_machine_metrics ON machine_metrics(machine_id, metric_type, timestamp);
CREATE INDEX idx_maintenance_schedule ON maintenance_schedule(machine_id, scheduled_date);
