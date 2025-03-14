CREATE TABLE product_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    sku VARCHAR(50) UNIQUE,
    price_modifier DECIMAL(10,2) DEFAULT 0,
    stock_quantity INT DEFAULT 0,
    specifications JSON,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE variation_combinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    variation_data JSON NOT NULL,
    price_impact DECIMAL(10,2) DEFAULT 0,
    is_available BOOLEAN DEFAULT true,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
