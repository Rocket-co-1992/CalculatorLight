CREATE TABLE resource_allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resource_type ENUM('machine', 'operator', 'material') NOT NULL,
    resource_id INT NOT NULL,
    job_id INT NOT NULL,
    allocated_from DATETIME NOT NULL,
    allocated_to DATETIME NOT NULL,
    status ENUM('scheduled', 'in_use', 'completed', 'cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE capacity_planning (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resource_id INT NOT NULL,
    resource_type VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    available_minutes INT NOT NULL,
    allocated_minutes INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `resource_date` (resource_id, resource_type, date)
);

CREATE TABLE production_shifts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    break_duration INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true
);
