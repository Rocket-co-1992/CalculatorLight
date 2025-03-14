CREATE TABLE editor_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    thumbnail VARCHAR(255),
    editor_data JSON NOT NULL,
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE editor_elements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('text', 'image', 'shape', 'qrcode') NOT NULL,
    name VARCHAR(100) NOT NULL,
    properties JSON,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE INDEX idx_editor_templates ON editor_templates(category_id, is_default);
