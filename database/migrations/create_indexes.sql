-- Performance indexes
CREATE INDEX idx_orders_user_status ON orders(user_id, status);
CREATE INDEX idx_order_items_product ON order_items(product_id);
CREATE INDEX idx_designs_user ON designs(user_id);
CREATE INDEX idx_analytics_event_type ON analytics_events(event_type);
CREATE INDEX idx_production_metrics_machine ON production_metrics(machine_id);
CREATE INDEX idx_inventory_material ON inventory_transactions(material_id);

-- Update existing indexes
ALTER TABLE orders ADD INDEX idx_user_status (user_id, status);
ALTER TABLE production_jobs ADD INDEX idx_status_date (status, created_at);
ALTER TABLE quotes ADD INDEX idx_status_date (status, created_at);
ALTER TABLE notifications ADD INDEX idx_user_read (user_id, read_at);
ALTER TABLE activity_logs ADD INDEX idx_entity (entity_type, entity_id);

-- Full-text search indexes
ALTER TABLE products ADD FULLTEXT INDEX ft_product_search (name, description);
ALTER TABLE orders ADD FULLTEXT INDEX ft_order_search (shipping_address, notes);
ALTER TABLE templates ADD FULLTEXT(name, description);
