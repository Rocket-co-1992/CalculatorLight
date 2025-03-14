CREATE TABLE editor_configurations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    settings JSON NOT NULL,
    last_canvas_state JSON,
    autosave_enabled BOOLEAN DEFAULT true,
    guides_enabled BOOLEAN DEFAULT true,
    grid_size INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE design_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    user_id INT NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    state_data JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_editor_config ON editor_configurations(user_id);
CREATE INDEX idx_design_history ON design_history(design_id, created_at);
