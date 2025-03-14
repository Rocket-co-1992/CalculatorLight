-- Add indexes for frequently joined tables
ALTER TABLE orders ADD INDEX idx_user_status_date (user_id, status, created_at);
ALTER TABLE order_items ADD INDEX idx_order_product (order_id, product_id);
ALTER TABLE quality_checks ADD INDEX idx_order_status (order_id, status);

-- Add fulltext search capabilities
ALTER TABLE products ADD FULLTEXT INDEX ft_product_search (name, description);
ALTER TABLE orders ADD FULLTEXT INDEX ft_order_search (notes);

-- Optimize for range queries
ALTER TABLE quality_thresholds ADD INDEX idx_threshold_ranges (check_type, min_value, max_value);

-- Add composite indexes for common filters
CREATE INDEX idx_workflow_tracking ON workflow_transitions(order_id, created_at);
CREATE INDEX idx_file_metadata ON file_storage(entity_type, created_at);
