CREATE TABLE production_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_number VARCHAR(50) UNIQUE,
    status VARCHAR(50) DEFAULT 'pending',
    scheduled_start DATETIME,
    scheduled_end DATETIME,
    actual_start DATETIME,
    actual_end DATETIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE batch_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    order_item_id INT NOT NULL,
    sequence_number INT,
    status VARCHAR(50) DEFAULT 'pending',
    completed_at DATETIME,
    quality_check_status VARCHAR(50),
    FOREIGN KEY (batch_id) REFERENCES production_batches(id),
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);

CREATE TABLE quality_inspections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_item_id INT NOT NULL,
    inspector_id INT NOT NULL,
    inspection_date DATETIME NOT NULL,
    result VARCHAR(50) NOT NULL,
    defects JSON,
    notes TEXT,
    FOREIGN KEY (batch_item_id) REFERENCES batch_items(id),
    FOREIGN KEY (inspector_id) REFERENCES users(id)
);
