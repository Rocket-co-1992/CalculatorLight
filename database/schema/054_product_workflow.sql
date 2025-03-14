CREATE TABLE product_workflows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    workflow_name VARCHAR(100) NOT NULL,
    stages JSON NOT NULL,
    estimated_time INT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE workflow_transitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT NOT NULL,
    from_stage VARCHAR(50) NOT NULL,
    to_stage VARCHAR(50) NOT NULL,
    required_role VARCHAR(50),
    conditions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (workflow_id) REFERENCES product_workflows(id)
);

CREATE TABLE job_stage_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    stage VARCHAR(50) NOT NULL,
    started_at TIMESTAMP NOT NULL,
    completed_at TIMESTAMP NULL,
    completed_by INT,
    notes TEXT,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (completed_by) REFERENCES users(id)
);

CREATE INDEX idx_workflow_product ON product_workflows(product_id);
CREATE INDEX idx_job_stages ON job_stage_history(job_id, stage);
