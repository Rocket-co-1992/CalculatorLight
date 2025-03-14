CREATE TABLE price_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    min_quantity INT,
    max_quantity INT,
    start_date DATE,
    end_date DATE,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_price_rules (
    product_id INT NOT NULL,
    price_rule_id INT NOT NULL,
    PRIMARY KEY (product_id, price_rule_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (price_rule_id) REFERENCES price_rules(id)
);

CREATE TABLE customer_price_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    level VARCHAR(50) NOT NULL,
    discount_percentage DECIMAL(5,2) DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_until DATE,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id)
);
