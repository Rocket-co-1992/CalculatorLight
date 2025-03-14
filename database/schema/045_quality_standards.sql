CREATE TABLE quality_standards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type_id INT NOT NULL,
    standard_name VARCHAR(100) NOT NULL,
    parameters JSON NOT NULL,
    tolerances JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_type_id) REFERENCES product_types(id)
);

CREATE TABLE quality_measurements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    standard_id INT NOT NULL,
    measured_values JSON NOT NULL,
    passed BOOLEAN,
    inspector_id INT NOT NULL,
    notes TEXT,
    measured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id),
    FOREIGN KEY (standard_id) REFERENCES quality_standards(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);

CREATE INDEX idx_quality_job ON quality_measurements(job_id, standard_id);
