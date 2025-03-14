CREATE TABLE file_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    allowed_types VARCHAR(255),
    max_size INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stored_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    size INT NOT NULL,
    path VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    checksum VARCHAR(64) NOT NULL,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES file_categories(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE INDEX idx_file_checksum ON stored_files(checksum);
CREATE INDEX idx_file_type ON stored_files(mime_type);
