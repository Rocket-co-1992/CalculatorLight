CREATE TABLE machines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'active',
    current_job_id INT,
    next_available_time TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE production_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    machine_id INT,
    status VARCHAR(50) NOT NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    estimated_time INT, -- in minutes
    actual_time INT,   -- in minutes
    quality_check_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE TABLE quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    production_job_id INT NOT NULL,
    checked_by_user_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (production_job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (checked_by_user_id) REFERENCES users(id)
);
