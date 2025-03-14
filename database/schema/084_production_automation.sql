CREATE TABLE automation_flows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    trigger_event VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON NOT NULL,
    priority INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE automation_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flow_id INT NOT NULL,
    execution_result JSON,
    status ENUM('success', 'failed') NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (flow_id) REFERENCES automation_flows(id)
);

CREATE INDEX idx_automation_trigger ON automation_flows(trigger_event, is_active);
