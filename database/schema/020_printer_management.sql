CREATE TABLE printer_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    color_profile VARCHAR(50),
    max_width INT,
    max_height INT,
    resolution_dpi INT,
    calibration_date DATE,
    status ENUM('active', 'maintenance', 'offline') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE printer_maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    maintenance_type VARCHAR(50) NOT NULL,
    scheduled_date DATE,
    completed_date DATE,
    technician_id INT,
    notes TEXT,
    status ENUM('scheduled', 'in_progress', 'completed') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printer_profiles(id),
    FOREIGN KEY (technician_id) REFERENCES users(id)
);
