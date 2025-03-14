CREATE TABLE reward_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    points_value INT NOT NULL,
    minimum_order DECIMAL(10,2),
    conditions JSON,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customer_rewards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    points_earned INT DEFAULT 0,
    points_spent INT DEFAULT 0,
    last_activity TIMESTAMP NULL,
    level VARCHAR(20) DEFAULT 'bronze',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE INDEX idx_customer_rewards ON customer_rewards(customer_id);
