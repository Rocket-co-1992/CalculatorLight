CREATE TABLE production_scheduling (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    machine_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    operator_id INT,
    priority INT DEFAULT 0,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (operator_id) REFERENCES users(id)
);

CREATE TABLE schedule_conflicts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT NOT NULL,
    conflict_type ENUM('overlap', 'maintenance', 'operator') NOT NULL,
    details JSON,
    is_resolved BOOLEAN DEFAULT false,
    resolved_by INT,
    resolved_at TIMESTAMP NULL,
    FOREIGN KEY (schedule_id) REFERENCES production_scheduling(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id)
);

CREATE INDEX idx_production_schedule ON production_scheduling(machine_id, start_time, status);
CREATE INDEX idx_schedule_conflicts ON schedule_conflicts(schedule_id, is_resolved);
