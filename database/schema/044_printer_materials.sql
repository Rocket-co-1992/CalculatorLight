CREATE TABLE material_compatibility (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    material_id INT NOT NULL,
    min_temperature INT,
    max_temperature INT,
    optimal_settings JSON,
    is_verified BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printer_profiles(id),
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE TABLE material_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    batch_number VARCHAR(50) NOT NULL,
    supplier_id INT NOT NULL,
    quantity_received DECIMAL(10,2),
    quantity_remaining DECIMAL(10,2),
    cost_per_unit DECIMAL(10,2),
    expiry_date DATE,
    storage_location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

CREATE INDEX idx_material_batch ON material_batches(material_id, batch_number);
