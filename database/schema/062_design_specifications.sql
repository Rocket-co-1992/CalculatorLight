CREATE TABLE product_specifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    min_width DECIMAL(10,2) NOT NULL,
    max_width DECIMAL(10,2) NOT NULL,
    min_height DECIMAL(10,2) NOT NULL,
    max_height DECIMAL(10,2) NOT NULL,
    resolution_dpi INT NOT NULL,
    bleed_margin INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE design_validations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    width DECIMAL(10,2),
    height DECIMAL(10,2),
    resolution INT,
    color_space VARCHAR(20),
    validation_errors JSON,
    is_valid BOOLEAN DEFAULT false,
    validated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id)
);
