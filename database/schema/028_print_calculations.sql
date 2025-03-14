CREATE TABLE material_calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    width_mm DECIMAL(10,2) NOT NULL,
    height_mm DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    waste_percentage DECIMAL(5,2) DEFAULT 10.00,
    total_area_m2 DECIMAL(10,4),
    total_cost DECIMAL(10,2),
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE TABLE price_calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    material_cost DECIMAL(10,2) NOT NULL,
    labor_cost DECIMAL(10,2) NOT NULL,
    finishing_cost DECIMAL(10,2) DEFAULT 0,
    markup_percentage DECIMAL(5,2),
    final_price DECIMAL(10,2) NOT NULL,
    calculation_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);
