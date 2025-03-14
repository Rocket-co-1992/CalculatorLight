CREATE TABLE automation_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('report', 'cleanup', 'notification', 'backup') NOT NULL,
    frequency VARCHAR(50) NOT NULL,
    last_run TIMESTAMP NULL,
    next_run TIMESTAMP NULL,
    configuration JSON,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE task_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    status ENUM('success', 'failed', 'warning') NOT NULL,
    execution_time INT,
    output TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES automation_tasks(id)
);
