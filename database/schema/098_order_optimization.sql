CREATE TABLE optimization_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    material_id INT NOT NULL,
    status ENUM('pending', 'processing', 'completed') DEFAULT 'pending',
    optimization_data JSON,
    material_saved_m2 DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE TABLE batch_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    order_item_id INT NOT NULL,
    position_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES optimization_batches(id),
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);

CREATE INDEX idx_optimization_status ON optimization_batches(status, created_at);
