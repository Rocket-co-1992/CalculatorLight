CREATE TABLE file_manager (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    file_hash VARCHAR(64) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE file_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    role VARCHAR(50) NOT NULL,
    permission_level ENUM('read', 'write', 'full') DEFAULT 'read',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (file_id) REFERENCES file_manager(id)
);

CREATE INDEX idx_file_search ON file_manager(file_hash, entity_type, entity_id);
