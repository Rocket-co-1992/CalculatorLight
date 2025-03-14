CREATE TABLE production_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    job_id INT NOT NULL,
    scheduled_start DATETIME NOT NULL,
    scheduled_end DATETIME NOT NULL,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') NOT NULL,
    operator_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (operator_id) REFERENCES users(id)
);

CREATE TABLE schedule_conflicts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    conflict_type ENUM('overlap', 'maintenance', 'capacity') NOT NULL,
    details JSON,
    resolved BOOLEAN DEFAULT false,
    resolved_by INT,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (schedule_id) REFERENCES production_schedules(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id)
);

CREATE INDEX idx_production_schedule ON production_schedules(machine_id, scheduled_start);
CREATE INDEX idx_schedule_conflicts ON schedule_conflicts(schedule_id, resolved);
