CREATE TABLE workflow_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    workflow_type ENUM('order', 'production', 'design') NOT NULL,
    steps JSON NOT NULL,
    conditions JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE workflow_instances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    current_step INT NOT NULL,
    status ENUM('pending', 'active', 'completed', 'failed') DEFAULT 'pending',
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (template_id) REFERENCES workflow_templates(id)
);

CREATE INDEX idx_workflow_entity ON workflow_instances(entity_type, entity_id);
