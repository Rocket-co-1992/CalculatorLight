CREATE TABLE communication_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    channel_type VARCHAR(50) NOT NULL,
    is_enabled BOOLEAN DEFAULT true,
    schedule_preferences JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    UNIQUE KEY unique_customer_channel (customer_id, channel_type)
);

CREATE TABLE message_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    content TEXT NOT NULL,
    variables JSON,
    channel_type VARCHAR(50) NOT NULL,
    trigger_event VARCHAR(50),
    active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_communication_prefs ON communication_preferences(customer_id);
CREATE INDEX idx_message_templates ON message_templates(trigger_event, active);
