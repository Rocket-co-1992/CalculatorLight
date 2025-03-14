CREATE TABLE printer_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    default_printer_id INT,
    color_profile VARCHAR(50),
    units ENUM('mm', 'inches', 'points') DEFAULT 'mm',
    grid_snap BOOLEAN DEFAULT true,
    auto_save_interval INT DEFAULT 300,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (default_printer_id) REFERENCES printer_profiles(id)
);

CREATE TABLE editor_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    default_template_id INT,
    interface_theme ENUM('light', 'dark', 'auto') DEFAULT 'auto',
    toolbar_position ENUM('left', 'right', 'top') DEFAULT 'left',
    shortcuts JSON,
    recent_templates JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (default_template_id) REFERENCES editor_templates(id)
);
