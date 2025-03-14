CREATE TABLE customer_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    customer_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    feedback_text TEXT,
    product_quality_rating INT,
    service_quality_rating INT,
    delivery_rating INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (customer_id) REFERENCES users(id)
);

CREATE TABLE feedback_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rating_id INT NOT NULL,
    responded_by INT NOT NULL,
    response_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rating_id) REFERENCES customer_ratings(id),
    FOREIGN KEY (responded_by) REFERENCES users(id)
);

CREATE INDEX idx_order_rating ON customer_ratings(order_id);
CREATE INDEX idx_customer_ratings ON customer_ratings(customer_id);
