CREATE TABLE payment_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL,
    reference VARCHAR(100),
    provider_response JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE payment_methods_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    method VARCHAR(50) NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT true,
    config_data JSON,
    min_amount DECIMAL(10,2),
    max_amount DECIMAL(10,2)
);

CREATE TABLE payment_webhooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider VARCHAR(50) NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    payload JSON,
    status VARCHAR(50) NOT NULL,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
