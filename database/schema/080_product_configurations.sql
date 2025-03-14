CREATE TABLE product_configurations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    config_key VARCHAR(50) NOT NULL,
    config_value JSON NOT NULL,
    validation_rules JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY unique_product_config (product_id, config_key)
);

CREATE TABLE product_templates_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    min_dpi INT NOT NULL,
    color_space VARCHAR(20) NOT NULL,
    dimensions JSON NOT NULL,
    safe_zone JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES design_templates(id)
);

CREATE INDEX idx_product_config ON product_configurations(product_id);
