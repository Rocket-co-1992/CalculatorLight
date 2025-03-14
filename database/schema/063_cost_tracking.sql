CREATE TABLE order_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    material_cost DECIMAL(10,2) NOT NULL,
    labor_cost DECIMAL(10,2) NOT NULL,
    machine_cost DECIMAL(10,2) NOT NULL,
    overhead_cost DECIMAL(10,2) NOT NULL,
    profit_margin DECIMAL(5,2) NOT NULL,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE machine_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    cost_per_hour DECIMAL(10,2) NOT NULL,
    maintenance_cost DECIMAL(10,2),
    depreciation_cost DECIMAL(10,2),
    valid_from DATE NOT NULL,
    valid_until DATE,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE INDEX idx_order_costs ON order_costs(order_id);
CREATE INDEX idx_machine_costs ON machine_costs(machine_id, valid_from);
