CREATE TABLE order_processing_stages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    estimated_time INT,
    sequence_order INT NOT NULL,
    notifications_enabled BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_stage_tracking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    stage_id INT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed', 'failed') NOT NULL,
    started_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    notes TEXT,
    completed_by INT,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (stage_id) REFERENCES order_processing_stages(id),
    FOREIGN KEY (completed_by) REFERENCES users(id)
);

CREATE INDEX idx_order_stages ON order_stage_tracking(order_id, stage_id);
CREATE INDEX idx_stage_status ON order_stage_tracking(status, started_at);
