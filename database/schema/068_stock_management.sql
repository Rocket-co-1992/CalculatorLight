CREATE TABLE stock_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    movement_type ENUM('in', 'out', 'adjustment') NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    reference_type VARCHAR(50),
    reference_id INT,
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE stock_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    alert_type ENUM('low_stock', 'out_of_stock', 'expiring') NOT NULL,
    threshold DECIMAL(10,2),
    email_notifications BOOLEAN DEFAULT true,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE INDEX idx_stock_movements ON stock_movements(material_id, created_at);
CREATE INDEX idx_stock_alerts ON stock_alerts(material_id, alert_type);
