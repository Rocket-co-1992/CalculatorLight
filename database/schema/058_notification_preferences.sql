CREATE TABLE notification_channels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT true,
    configuration JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_notification_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    notification_type VARCHAR(50) NOT NULL,
    channel_id INT NOT NULL,
    is_enabled BOOLEAN DEFAULT true,
    schedule JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (channel_id) REFERENCES notification_channels(id),
    UNIQUE KEY unique_user_notif (user_id, notification_type, channel_id)
);

CREATE INDEX idx_notif_pref_user ON user_notification_preferences(user_id);
