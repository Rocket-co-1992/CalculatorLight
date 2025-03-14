CREATE TABLE design_validations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    validator_type VARCHAR(50) NOT NULL,
    validation_rules JSON NOT NULL,
    validation_result JSON,
    is_valid BOOLEAN,
    errors JSON,
    validated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id)
);

CREATE TABLE validation_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_type_id INT NOT NULL,
    rule_name VARCHAR(100) NOT NULL,
    rule_type ENUM('dimension', 'resolution', 'color', 'bleed', 'safety') NOT NULL,
    parameters JSON NOT NULL,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_type_id) REFERENCES product_types(id)
);

CREATE INDEX idx_design_validations ON design_validations(design_id);
CREATE INDEX idx_validation_rules ON validation_rules(product_type_id, rule_type);
