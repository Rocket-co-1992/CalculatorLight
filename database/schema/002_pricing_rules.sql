CREATE TABLE pricing_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    min_quantity INT,
    max_quantity INT,
    discount_percentage DECIMAL(5,2),
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE finishing_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    price_per_unit DECIMAL(10,2),
    minimum_charge DECIMAL(10,2),
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_finishing_options (
    product_id INT NOT NULL,
    finishing_option_id INT NOT NULL,
    price_modifier DECIMAL(5,2) DEFAULT 1.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (product_id, finishing_option_id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (finishing_option_id) REFERENCES finishing_options(id)
);
