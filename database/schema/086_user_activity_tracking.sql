CREATE TABLE user_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    object_type VARCHAR(50) NOT NULL,
    object_id INT NOT NULL,
    action_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE design_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    changes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_user_actions ON user_actions(user_id, action_type);
CREATE INDEX idx_design_history ON design_history(design_id, created_at);
