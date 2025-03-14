CREATE TABLE file_storage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT NOT NULL,
    path VARCHAR(255) NOT NULL,
    checksum VARCHAR(64) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    uploaded_by INT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE TABLE file_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT NOT NULL,
    version INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    size INT NOT NULL,
    checksum VARCHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (file_id) REFERENCES file_storage(id)
);

-- Add indexes for performance
CREATE INDEX idx_file_entity ON file_storage(entity_type, entity_id);
CREATE INDEX idx_file_checksum ON file_storage(checksum);
