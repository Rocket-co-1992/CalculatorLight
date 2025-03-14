-- Optimize frequently accessed tables
ALTER TABLE orders ADD INDEX idx_customer_date (user_id, created_at);
ALTER TABLE order_items ADD INDEX idx_product_date (product_id, created_at);
ALTER TABLE price_calculations ADD INDEX idx_order_item (order_item_id);
ALTER TABLE material_calculations ADD INDEX idx_material (material_id);

-- Add table partitioning for large tables
ALTER TABLE analytics_events
PARTITION BY RANGE (UNIX_TIMESTAMP(created_at)) (
    PARTITION p_2023_01 VALUES LESS THAN (UNIX_TIMESTAMP('2023-02-01 00:00:00')),
    PARTITION p_2023_02 VALUES LESS THAN (UNIX_TIMESTAMP('2023-03-01 00:00:00')),
    PARTITION p_max VALUES LESS THAN MAXVALUE
);

-- Add composite indexes for common queries
CREATE INDEX idx_order_status_date ON orders(status, created_at);
CREATE INDEX idx_material_stock ON materials(active, stock_quantity);
