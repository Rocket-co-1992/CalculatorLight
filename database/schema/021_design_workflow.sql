CREATE TABLE design_workflows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    steps JSON NOT NULL,
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE design_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    workflow_step VARCHAR(50) NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    feedback TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
);
