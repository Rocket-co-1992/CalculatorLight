CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'customer', 'designer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    category_id INT,
    width INT,
    height INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    design_data JSON,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    method VARCHAR(50) NOT NULL,
    tracking_number VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE shipping_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    is_default BOOLEAN DEFAULT false,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    min_quantity INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    data JSON,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE design_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    product_id INT,
    design_data JSON NOT NULL,
    thumbnail VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE design_proofs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_notes TEXT,
    customer_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);

CREATE TABLE production_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    printer_id INT NOT NULL,
    status ENUM('queued', 'printing', 'completed', 'failed') DEFAULT 'queued',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    vat_amount DECIMAL(10,2) NOT NULL,
    issued_date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE design_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    design_id INT NOT NULL,
    elements JSON NOT NULL,
    metadata JSON,
    preview_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (design_id) REFERENCES design_templates(id)
);

CREATE TABLE design_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    version_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (version_id) REFERENCES design_versions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE printers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    capabilities JSON,
    status ENUM('active', 'maintenance', 'offline') DEFAULT 'active',
    last_maintenance_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE printer_consumables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    level INT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printers(id)
);

CREATE TABLE maintenance_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    maintenance_type VARCHAR(100) NOT NULL,
    scheduled_date DATE NOT NULL,
    status ENUM('pending', 'completed', 'skipped') DEFAULT 'pending',
    notes TEXT,
    FOREIGN KEY (printer_id) REFERENCES printers(id)
);

CREATE TABLE production_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_number VARCHAR(50) NOT NULL UNIQUE,
    status ENUM('pending', 'processing', 'completed') DEFAULT 'pending',
    printer_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printers(id)
);

CREATE TABLE batch_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    order_item_id INT NOT NULL,
    position INT NOT NULL,
    FOREIGN KEY (batch_id) REFERENCES production_batches(id),
    FOREIGN KEY (order_item_id) REFERENCES order_items(id)
);

CREATE TABLE quality_checks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    check_type VARCHAR(50) NOT NULL,
    status ENUM('passed', 'failed') NOT NULL,
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE production_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    issue_type VARCHAR(50) NOT NULL,
    description TEXT,
    severity ENUM('low', 'medium', 'high') NOT NULL,
    status ENUM('open', 'resolved') DEFAULT 'open',
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE production_costs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    material_cost DECIMAL(10,2) NOT NULL,
    printing_cost DECIMAL(10,2) NOT NULL,
    finishing_cost DECIMAL(10,2) NOT NULL,
    labor_cost DECIMAL(10,2) NOT NULL,
    overhead_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    calculated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE production_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    order_id INT NOT NULL,
    scheduled_start DATETIME NOT NULL,
    estimated_duration INT NOT NULL, -- in minutes
    priority INT DEFAULT 0,
    status ENUM('scheduled', 'in_progress', 'completed') DEFAULT 'scheduled',
    FOREIGN KEY (printer_id) REFERENCES printers(id),
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE subscription_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    billing_cycle ENUM('monthly', 'yearly') NOT NULL,
    features JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    status ENUM('active', 'cancelled', 'expired') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    last_billing_date DATE,
    next_billing_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(id)
);

CREATE TABLE subscription_invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subscription_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'failed') DEFAULT 'pending',
    billing_date DATE NOT NULL,
    paid_date DATE,
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
);

CREATE TABLE machine_capacity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    date DATE NOT NULL,
    total_minutes INT NOT NULL DEFAULT 480,
    scheduled_minutes INT NOT NULL DEFAULT 0,
    maintenance_minutes INT NOT NULL DEFAULT 0,
    FOREIGN KEY (printer_id) REFERENCES printers(id)
);

CREATE TABLE setup_matrices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_job_type VARCHAR(50) NOT NULL,
    to_job_type VARCHAR(50) NOT NULL,
    setup_time INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE shipping_boxes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    length DECIMAL(10,2) NOT NULL,
    width DECIMAL(10,2) NOT NULL,
    height DECIMAL(10,2) NOT NULL,
    max_weight DECIMAL(10,2) NOT NULL,
    cost DECIMAL(10,2) NOT NULL,
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    box_id INT NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    items JSON NOT NULL,
    tracking_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (box_id) REFERENCES shipping_boxes(id)
);

CREATE TABLE material_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    material_type VARCHAR(50) NOT NULL,
    quantity_used DECIMAL(10,2) NOT NULL,
    waste_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE material_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_type VARCHAR(50) NOT NULL,
    quantity_available DECIMAL(10,2) NOT NULL,
    reorder_point DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE production_metrics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    setup_time INT,
    completion_time INT,
    machine_efficiency FLOAT,
    quality_score FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES production_queue(id)
);

CREATE TABLE ml_models (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    version VARCHAR(50) NOT NULL,
    model_data LONGBLOB,
    accuracy_score FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE error_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    error_message TEXT NOT NULL,
    error_code INT,
    stack_trace TEXT,
    context JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    payment_terms INT DEFAULT 30,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE purchase_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    status ENUM('pending', 'approved', 'ordered', 'received', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    expected_delivery DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
);

CREATE TABLE purchase_order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id INT NOT NULL,
    material_type VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id)
);

CREATE TABLE color_calibrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    calibration_data JSON NOT NULL,
    color_profile BLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid_until TIMESTAMP,
    FOREIGN KEY (printer_id) REFERENCES printers(id)
);

CREATE TABLE color_measurements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    calibration_id INT NOT NULL,
    measurement_type VARCHAR(50) NOT NULL,
    values JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (calibration_id) REFERENCES color_calibrations(id)
);

CREATE TABLE cron_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    handler_class VARCHAR(255) NOT NULL,
    frequency VARCHAR(50) NOT NULL,
    last_run TIMESTAMP NULL,
    next_run TIMESTAMP NOT NULL,
    params JSON,
    status ENUM('active', 'paused') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cron_task_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT NOT NULL,
    status ENUM('success', 'failure') NOT NULL,
    execution_time FLOAT NOT NULL,
    result JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES cron_tasks(id)
);

CREATE TABLE media_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL,
    specs JSON NOT NULL,
    color_profile BLOB,
    printer_settings JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE printer_media_compatibility (
    id INT AUTO_INCREMENT PRIMARY KEY,
    printer_id INT NOT NULL,
    media_profile_id INT NOT NULL,
    validated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_compatible BOOLEAN DEFAULT true,
    validation_notes TEXT,
    FOREIGN KEY (printer_id) REFERENCES printers(id),
    FOREIGN KEY (media_profile_id) REFERENCES media_profiles(id)
);
