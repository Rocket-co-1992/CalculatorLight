CREATE TABLE workflow_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    trigger_event VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON,
    priority INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE workflow_executions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_id INT NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    status ENUM('pending', 'success', 'failed') NOT NULL,
    result_data JSON,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rule_id) REFERENCES workflow_rules(id)
);

CREATE INDEX idx_workflow_trigger ON workflow_rules(trigger_event, is_active);
CREATE INDEX idx_workflow_executions ON workflow_executions(rule_id, status);
