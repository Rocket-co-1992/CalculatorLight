CREATE TABLE custom_field_definitions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    field_type ENUM('text', 'number', 'select', 'color', 'size') NOT NULL,
    validation_rules JSON,
    options JSON,
    required BOOLEAN DEFAULT false,
    display_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE product_dimension_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    min_width DECIMAL(10,2) NOT NULL,
    max_width DECIMAL(10,2) NOT NULL,
    min_height DECIMAL(10,2) NOT NULL,
    max_height DECIMAL(10,2) NOT NULL,
    measurement_unit ENUM('mm', 'cm', 'inches') DEFAULT 'mm',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE INDEX idx_custom_fields ON custom_field_definitions(product_id, display_order);
