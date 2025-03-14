CREATE TABLE template_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT NULL,
    sort_order INT DEFAULT 0,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES template_categories(id)
);

CREATE TABLE product_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    preview_image VARCHAR(255),
    template_data JSON NOT NULL,
    dimensions JSON,
    is_featured BOOLEAN DEFAULT false,
    downloads INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES template_categories(id)
);

CREATE INDEX idx_template_category ON product_templates(category_id, is_featured);
