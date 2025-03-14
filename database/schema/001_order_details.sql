CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    material_id INT NOT NULL,
    quantity INT NOT NULL,
    width DECIMAL(10,2),
    height DECIMAL(10,2),
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    design_data JSON,
    finishing_options JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE TABLE production_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    assigned_to INT,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    estimated_time INT,
    actual_time INT,
    machine_id INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (assigned_to) REFERENCES users(id)
);

CREATE TABLE machines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'active',
    maintenance_due DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
