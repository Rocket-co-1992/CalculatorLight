CREATE TABLE shipping_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    api_key VARCHAR(255),
    config_data JSON,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE shipping_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    provider_id INT NOT NULL,
    tracking_number VARCHAR(100),
    status VARCHAR(50),
    last_update TIMESTAMP NULL,
    tracking_history JSON,
    estimated_delivery DATE,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (provider_id) REFERENCES shipping_providers(id)
);

CREATE INDEX idx_shipping_tracking ON shipping_tracking(tracking_number);
