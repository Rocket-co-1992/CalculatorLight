CREATE TABLE file_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    allowed_extensions VARCHAR(255),
    max_file_size INT,
    path_prefix VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stored_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_hash VARCHAR(64) NOT NULL,
    mime_type VARCHAR(100),
    size INT NOT NULL,
    metadata JSON,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES file_categories(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE INDEX idx_file_hash ON stored_files(file_hash);
