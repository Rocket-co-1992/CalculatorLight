DELIMITER //

-- Update stock levels after order completion
CREATE TRIGGER after_order_complete
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        UPDATE materials m
        INNER JOIN order_items oi ON oi.material_id = m.id
        SET m.stock_quantity = m.stock_quantity - oi.quantity
        WHERE oi.order_id = NEW.id;
    END IF;
END //

-- Track price changes
CREATE TRIGGER before_product_price_update
BEFORE UPDATE ON products
FOR EACH ROW
BEGIN
    IF NEW.base_price != OLD.base_price THEN
        INSERT INTO price_history (
            product_id, old_price, new_price, changed_at
        ) VALUES (
            NEW.id, OLD.base_price, NEW.base_price, NOW()
        );
    END IF;
END //

DELIMITER ;
