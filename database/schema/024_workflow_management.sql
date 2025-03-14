CREATE TABLE workflow_steps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    estimated_time INT,
    department_id INT,
    sequence_number INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

CREATE TABLE job_workflows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    current_step INT,
    status ENUM('pending', 'in_progress', 'completed', 'on_hold') DEFAULT 'pending',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (current_step) REFERENCES workflow_steps(id)
);
