CREATE TABLE customization_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    option_name VARCHAR(100) NOT NULL,
    option_type ENUM('size', 'material', 'color', 'finish') NOT NULL,
    required BOOLEAN DEFAULT false,
    configuration JSON,
    price_impact JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE custom_product_presets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    product_id INT NOT NULL,
    customization_data JSON NOT NULL,
    is_public BOOLEAN DEFAULT false,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE INDEX idx_product_options ON customization_options(product_id, option_type);
