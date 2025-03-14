CREATE TABLE production_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    cost_type ENUM('material', 'labor', 'machine', 'overhead') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    quantity DECIMAL(10,2),
    unit_price DECIMAL(10,2),
    notes TEXT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE TABLE material_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    supplier_id INT NOT NULL,
    purchase_date DATE NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    invoice_number VARCHAR(50),
    FOREIGN KEY (material_id) REFERENCES materials(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

CREATE TABLE machine_operating_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    cost_type VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    period_start DATE,
    period_end DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);
