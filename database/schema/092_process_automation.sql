CREATE TABLE workflow_processes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    process_type ENUM('order', 'production', 'shipping') NOT NULL,
    steps JSON NOT NULL,
    triggers JSON,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE process_executions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    process_id INT NOT NULL,
    entity_id INT NOT NULL,
    current_step VARCHAR(50) NOT NULL,
    status ENUM('running', 'completed', 'failed', 'cancelled') NOT NULL,
    execution_data JSON,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (process_id) REFERENCES workflow_processes(id)
);

CREATE INDEX idx_process_status ON process_executions(process_id, status);
