CREATE TABLE shipping_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zone_id INT NOT NULL,
    weight_from DECIMAL(10,2),
    weight_to DECIMAL(10,2),
    price DECIMAL(10,2) NOT NULL,
    delivery_time_min INT,
    delivery_time_max INT,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (zone_id) REFERENCES shipping_zones(id)
);

CREATE TABLE delivery_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    delivery_date DATE NOT NULL,
    time_slot VARCHAR(50),
    status VARCHAR(50) DEFAULT 'scheduled',
    driver_notes TEXT,
    tracking_number VARCHAR(100),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
