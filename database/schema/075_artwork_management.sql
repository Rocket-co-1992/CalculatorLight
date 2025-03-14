CREATE TABLE artwork_specifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    width_mm DECIMAL(10,2) NOT NULL,
    height_mm DECIMAL(10,2) NOT NULL,
    bleed_mm DECIMAL(10,2) NOT NULL,
    safe_zone_mm DECIMAL(10,2) NOT NULL,
    min_resolution INT NOT NULL,
    color_profile VARCHAR(50) NOT NULL,
    file_format VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE artwork_validations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    artwork_file VARCHAR(255) NOT NULL,
    validation_results JSON NOT NULL,
    status ENUM('pending', 'approved', 'rejected') NOT NULL,
    checked_by INT,
    checked_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (checked_by) REFERENCES users(id)
);

CREATE INDEX idx_artwork_specs ON artwork_specifications(product_id);
CREATE INDEX idx_artwork_validation ON artwork_validations(order_item_id, status);
