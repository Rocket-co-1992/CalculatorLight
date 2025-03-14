CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    changed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
);

CREATE TABLE production_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    machine_id INT NOT NULL,
    scheduled_start TIMESTAMP NOT NULL,
    scheduled_end TIMESTAMP NOT NULL,
    priority INT DEFAULT 0,
    status VARCHAR(50) DEFAULT 'scheduled',
    notes TEXT,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE TABLE quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    checked_by INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    comments TEXT,
    checklist JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (checked_by) REFERENCES users(id)
);
