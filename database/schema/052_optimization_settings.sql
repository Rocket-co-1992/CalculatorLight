CREATE TABLE optimization_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rule_type VARCHAR(50) NOT NULL,
    conditions JSON,
    actions JSON,
    priority INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE optimization_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_id INT NOT NULL,
    schedule_type ENUM('daily', 'weekly', 'monthly') NOT NULL,
    execute_at TIME NOT NULL,
    last_run TIMESTAMP NULL,
    next_run TIMESTAMP NULL,
    FOREIGN KEY (rule_id) REFERENCES optimization_rules(id)
);

CREATE TABLE optimization_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_id INT NOT NULL,
    execution_time INT NOT NULL,
    improvements JSON,
    savings_estimate DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rule_id) REFERENCES optimization_rules(id)
);
