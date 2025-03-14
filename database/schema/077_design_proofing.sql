CREATE TABLE design_proofs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    version INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    preview_url VARCHAR(255),
    comments TEXT,
    approved_by INT,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES saved_designs(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

CREATE TABLE proof_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    proof_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    coordinates JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (proof_id) REFERENCES design_proofs(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_design_proofs ON design_proofs(design_id, version);
