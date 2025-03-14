CREATE TABLE approval_workflows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    steps JSON NOT NULL,
    required_roles JSON,
    timeout_minutes INT DEFAULT 1440,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE approval_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    current_step INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    requested_by INT NOT NULL,
    expires_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (workflow_id) REFERENCES approval_workflows(id),
    FOREIGN KEY (requested_by) REFERENCES users(id)
);

CREATE INDEX idx_approval_entity ON approval_requests(entity_type, entity_id);
CREATE INDEX idx_approval_status ON approval_requests(status, expires_at);
