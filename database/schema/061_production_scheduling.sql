CREATE TABLE production_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    capacity_minutes INT NOT NULL,
    booked_minutes INT DEFAULT 0,
    status ENUM('available', 'partial', 'full') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE TABLE job_scheduling (
    id INT AUTO_INCREMENT PRIMARY KEY,
    production_job_id INT NOT NULL,
    slot_id INT NOT NULL,
    minutes_required INT NOT NULL,
    priority INT DEFAULT 0,
    scheduled_start DATETIME,
    scheduled_end DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (production_job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (slot_id) REFERENCES production_slots(id)
);

CREATE INDEX idx_slot_availability ON production_slots(machine_id, start_time, status);
CREATE INDEX idx_job_schedule ON job_scheduling(production_job_id, scheduled_start);
