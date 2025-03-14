CREATE TABLE material_rates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    material_id INT NOT NULL,
    base_rate DECIMAL(10,2) NOT NULL,
    min_quantity INT NOT NULL DEFAULT 1,
    discount_rate DECIMAL(4,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (material_id) REFERENCES materials(id),
    INDEX idx_material_rates (material_id)
);

CREATE TABLE finishing_rates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    finishing_id INT NOT NULL,
    base_rate DECIMAL(10,2) NOT NULL,
    setup_cost DECIMAL(10,2) DEFAULT 0.00,
    min_quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (finishing_id) REFERENCES finishing_types(id),
    INDEX idx_finishing_rates (finishing_id)
);
