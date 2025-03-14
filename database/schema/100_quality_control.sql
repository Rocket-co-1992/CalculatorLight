CREATE TABLE quality_checkpoints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    required_measurements JSON,
    acceptance_criteria JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE quality_inspections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    checkpoint_id INT NOT NULL,
    inspector_id INT NOT NULL,
    measurements JSON,
    passed BOOLEAN NOT NULL,
    notes TEXT,
    inspection_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (checkpoint_id) REFERENCES quality_checkpoints(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);

CREATE INDEX idx_inspection_item ON quality_inspections(order_item_id);
CREATE INDEX idx_inspection_date ON quality_inspections(inspection_date);
