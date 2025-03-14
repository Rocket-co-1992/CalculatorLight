CREATE TABLE quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    inspector_id INT NOT NULL,
    check_type VARCHAR(50) NOT NULL,
    status ENUM('passed', 'failed', 'warning') NOT NULL,
    measurements JSON,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);

CREATE TABLE quality_thresholds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    check_type VARCHAR(50) NOT NULL,
    material_id INT,
    product_id INT,
    min_value DECIMAL(10,4),
    max_value DECIMAL(10,4),
    unit VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
