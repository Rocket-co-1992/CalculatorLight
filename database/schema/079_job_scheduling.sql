CREATE TABLE production_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    job_id INT NOT NULL,
    scheduled_start DATETIME NOT NULL,
    scheduled_end DATETIME NOT NULL,
    priority INT DEFAULT 0,
    locked BOOLEAN DEFAULT false,
    conflict_group_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE schedule_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    reason VARCHAR(50) NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE INDEX idx_schedule_machine ON production_schedules(machine_id, scheduled_start);
CREATE INDEX idx_schedule_blocks ON schedule_blocks(machine_id, start_time, end_time);
