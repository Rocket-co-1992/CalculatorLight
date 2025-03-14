CREATE TABLE machine_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    day_of_week TINYINT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    capacity_per_hour INT NOT NULL,
    active BOOLEAN DEFAULT true,
    FOREIGN KEY (machine_id) REFERENCES machines(id)
);

CREATE TABLE machine_reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    job_id INT NOT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    priority INT DEFAULT 0,
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    FOREIGN KEY (machine_id) REFERENCES machines(id),
    FOREIGN KEY (job_id) REFERENCES production_jobs(id)
);

CREATE INDEX idx_machine_schedule ON machine_reservations(machine_id, start_datetime);
CREATE INDEX idx_reservation_status ON machine_reservations(status, start_datetime);
