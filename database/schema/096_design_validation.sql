CREATE TABLE validation_rules_registry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_name VARCHAR(100) NOT NULL,
    rule_type ENUM('dimension', 'resolution', 'color', 'format') NOT NULL,
    parameters JSON NOT NULL,
    error_message VARCHAR(255),
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE design_validations_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    validation_results JSON NOT NULL,
    passed BOOLEAN NOT NULL,
    validation_time INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id)
);

CREATE INDEX idx_design_validations ON design_validations_log(design_id, passed);
