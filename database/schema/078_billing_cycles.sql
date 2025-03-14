CREATE TABLE billing_cycles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_amount DECIMAL(10,2) DEFAULT 0,
    status ENUM('open', 'closed', 'invoiced') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id)
);

CREATE TABLE cycle_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cycle_id INT NOT NULL,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    included_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cycle_id) REFERENCES billing_cycles(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE INDEX idx_billing_customer ON billing_cycles(customer_id, start_date);
CREATE INDEX idx_cycle_orders ON cycle_items(cycle_id, order_id);
