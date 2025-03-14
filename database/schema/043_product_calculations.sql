CREATE TABLE calculation_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    rule_type ENUM('size', 'quantity', 'material', 'finishing') NOT NULL,
    conditions JSON NOT NULL,
    price_modifier DECIMAL(10,4) NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE price_calculations_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    calculation_data JSON NOT NULL,
    rules_applied JSON,
    final_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);

CREATE INDEX idx_calculation_product ON calculation_rules(product_id, rule_type);
CREATE INDEX idx_price_calculations ON price_calculations_log(order_item_id);
