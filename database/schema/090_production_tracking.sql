CREATE TABLE production_stations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    status ENUM('active', 'maintenance', 'offline') DEFAULT 'active',
    current_job_id INT,
    last_maintenance_date DATE,
    next_maintenance_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE job_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    station_id INT NOT NULL,
    operator_id INT,
    start_time TIMESTAMP NULL,
    end_time TIMESTAMP NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (station_id) REFERENCES production_stations(id),
    FOREIGN KEY (operator_id) REFERENCES users(id)
);

CREATE INDEX idx_job_tracking ON job_tracking(job_id, station_id);
