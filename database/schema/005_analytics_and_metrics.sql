CREATE TABLE analytics_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    user_id INT,
    data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE production_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    job_id INT NOT NULL,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    quantity_produced INT,
    waste_quantity INT,
    quality_score DECIMAL(3,2),
    notes TEXT,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE inventory_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    transaction_type ENUM('in', 'out', 'adjust') NOT NULL,
    quantity INT NOT NULL,
    reference_id INT,
    reference_type VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);
