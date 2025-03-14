CREATE TABLE financial_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    transaction_type ENUM('payment', 'refund', 'credit', 'debit') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    status ENUM('pending', 'completed', 'failed', 'cancelled') NOT NULL,
    payment_method VARCHAR(50),
    reference_id VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE tax_rates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_code VARCHAR(2) NOT NULL,
    tax_type VARCHAR(50) NOT NULL,
    rate DECIMAL(5,2) NOT NULL,
    valid_from DATE NOT NULL,
    valid_until DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_tax_rate (country_code, tax_type, valid_from)
);

CREATE INDEX idx_financial_order ON financial_transactions(order_id, status);
