CREATE TABLE external_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    credentials JSON,
    config_data JSON,
    is_active BOOLEAN DEFAULT true,
    last_sync TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE service_webhooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    endpoint_url VARCHAR(255) NOT NULL,
    event_types JSON,
    secret_token VARCHAR(255),
    is_active BOOLEAN DEFAULT true,
    last_triggered TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES external_services(id)
);

CREATE TABLE integration_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    request_data JSON,
    response_data JSON,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES external_services(id)
);
