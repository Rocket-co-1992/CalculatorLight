CREATE TABLE shipping_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    carrier VARCHAR(50) NOT NULL,
    delivery_time_min INT,
    delivery_time_max INT,
    base_cost DECIMAL(10,2),
    weight_cost_multiplier DECIMAL(10,4),
    active BOOLEAN DEFAULT true
);

CREATE TABLE shipping_zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    countries TEXT NOT NULL,
    shipping_method_id INT,
    price_modifier DECIMAL(5,2) DEFAULT 1.00,
    FOREIGN KEY (shipping_method_id) REFERENCES shipping_methods(id)
);

CREATE TABLE order_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    tracking_number VARCHAR(100),
    carrier_reference VARCHAR(100),
    status VARCHAR(50),
    location VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
