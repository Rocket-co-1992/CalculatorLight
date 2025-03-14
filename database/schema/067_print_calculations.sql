CREATE TABLE pricing_formulas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    formula_type ENUM('area', 'quantity', 'hybrid') NOT NULL,
    base_formula TEXT NOT NULL,
    conditions JSON,
    priority INT DEFAULT 0,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE material_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    quantity_from INT,
    quantity_to INT,
    price_per_unit DECIMAL(10,4) NOT NULL,
    valid_from DATE NOT NULL,
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE INDEX idx_pricing_formulas ON pricing_formulas(formula_type, priority);
CREATE INDEX idx_material_costs ON material_costs(material_id, valid_from, valid_until);
