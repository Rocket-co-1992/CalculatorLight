CREATE TABLE scheduled_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(100) NOT NULL,
    frequency VARCHAR(50) NOT NULL,
    last_run TIMESTAMP NULL,
    next_run TIMESTAMP NULL,
    parameters JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE task_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    start_time TIMESTAMP NOT NULL,
    end_time TIMESTAMP NULL,
    status ENUM('success', 'failed', 'running') NOT NULL,
    output TEXT,
    error_message TEXT,
    FOREIGN KEY (task_id) REFERENCES scheduled_tasks(id)
);

CREATE TABLE workflow_automations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trigger_event VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
