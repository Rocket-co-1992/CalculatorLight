CREATE TABLE printer_queues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    job_id INT NOT NULL,
    priority INT DEFAULT 0,
    file_path VARCHAR(255) NOT NULL,
    print_settings JSON,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    error_message TEXT,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (printer_id) REFERENCES printer_profiles(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE printer_calibrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    technician_id INT NOT NULL,
    calibration_type VARCHAR(50) NOT NULL,
    measurements JSON,
    color_profile VARCHAR(100),
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printer_profiles(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);

CREATE INDEX idx_printer_queue ON printer_queues(printer_id, status, priority);
