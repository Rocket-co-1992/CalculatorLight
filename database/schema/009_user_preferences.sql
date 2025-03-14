CREATE TABLE user_preferences (
    user_id INT PRIMARY KEY,
    language VARCHAR(5) DEFAULT 'pt',
    currency VARCHAR(3) DEFAULT 'EUR',
    notifications_enabled BOOLEAN DEFAULT true,
    theme VARCHAR(20) DEFAULT 'dark',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE notification_settings (
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    enabled BOOLEAN DEFAULT true,
    email_enabled BOOLEAN DEFAULT true,
    sms_enabled BOOLEAN DEFAULT false,
    PRIMARY KEY (user_id, type),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
