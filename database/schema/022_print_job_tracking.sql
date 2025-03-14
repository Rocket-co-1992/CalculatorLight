CREATE TABLE print_queues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    job_id INT NOT NULL,
    priority INT DEFAULT 0,
    status ENUM('queued', 'printing', 'completed', 'failed') DEFAULT 'queued',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    error_log TEXT,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE printer_maintenance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    maintenance_type VARCHAR(50) NOT NULL,
    technician_id INT,
    description TEXT,
    parts_replaced TEXT,
    cost DECIMAL(10,2),
    maintenance_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    next_maintenance_date DATE,
    FOREIGN KEY (printer_id) REFERENCES printer_profiles(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);

CREATE TABLE quality_control_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    print_queue_id INT NOT NULL,
    inspector_id INT NOT NULL,
    color_accuracy DECIMAL(5,2),
    resolution_check BOOLEAN,
    alignment_check BOOLEAN,
    material_quality BOOLEAN,
    notes TEXT,
    approved BOOLEAN DEFAULT false,
    checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (print_queue_id) REFERENCES print_queues(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);
