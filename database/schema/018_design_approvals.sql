CREATE TABLE design_approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    comments TEXT,
    technical_checks JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
);

CREATE TABLE design_revisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    version INT NOT NULL,
    changes_description TEXT,
    design_data JSON,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
