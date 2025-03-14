CREATE TABLE production_calendar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    date DATE NOT NULL,
    shift_start TIME NOT NULL,
    shift_end TIME NOT NULL,
    available_minutes INT NOT NULL,
    scheduled_minutes INT DEFAULT 0,
    maintenance_minutes INT DEFAULT 0,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    UNIQUE KEY `unique_machine_date` (machine_id, date)
);

CREATE TABLE production_efficiency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    date DATE NOT NULL,
    total_jobs INT DEFAULT 0,
    successful_jobs INT DEFAULT 0,
    failed_jobs INT DEFAULT 0,
    total_runtime_minutes INT DEFAULT 0,
    downtime_minutes INT DEFAULT 0,
    efficiency_score DECIMAL(5,2),
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE INDEX idx_production_date ON production_calendar(date);
CREATE INDEX idx_efficiency_machine ON production_efficiency(machine_id, date);
