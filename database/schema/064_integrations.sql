CREATE TABLE integration_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('shipping', 'payment', 'accounting', 'crm') NOT NULL,
    config_schema JSON NOT NULL,
    webhook_format JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE provider_credentials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    credentials JSON NOT NULL,
    is_test_mode BOOLEAN DEFAULT false,
    last_verified TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES integration_providers(id)
);

CREATE TABLE integration_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    request_data JSON,
    response_data JSON,
    status VARCHAR(20) NOT NULL,
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES integration_providers(id)
);

CREATE INDEX idx_integration_events ON integration_events(provider_id, event_type);
