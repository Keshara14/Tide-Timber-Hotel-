CREATE DATABASE IF NOT EXISTS coastal_villa_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coastal_villa_db;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contact_number VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('guest','admin') NOT NULL DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE rooms (
    room_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    facilities TEXT NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests INT UNSIGNED NOT NULL,
    room_image VARCHAR(255) NOT NULL,
    room_status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_rooms_status_type (room_status, room_type)
) ENGINE=InnoDB;

CREATE TABLE bookings (
    booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('Pending','Confirmed','Cancelled') NOT NULL DEFAULT 'Pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_user FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_bookings_room FOREIGN KEY (room_id) REFERENCES rooms(room_id)
        ON DELETE RESTRICT,
    INDEX idx_booking_status (booking_status),
    INDEX idx_booking_dates (room_id, check_in, check_out)
) ENGINE=InnoDB;

-- Sample password for both accounts: password
INSERT INTO users (name, email, contact_number, password, role) VALUES
('Villa Administrator', 'admin@tideandtimber.lk', '+94 77 111 2222',
 '$2y$10$T8WjJoD34.1SF70KErpK..VU38c21wgVi3BcitgEI6V1Pji28kxeq', 'admin'),
('Amaya Perera', 'guest@example.com', '+94 71 234 5678',
 '$2y$10$T8WjJoD34.1SF70KErpK..VU38c21wgVi3BcitgEI6V1Pji28kxeq', 'guest');

INSERT INTO rooms (room_name, room_type, description, facilities, price_per_night, max_guests, room_image, room_status) VALUES
('Ocean View Suite', 'Suite',
 'A serene top-floor suite with uninterrupted Indian Ocean views, a handcrafted king bed and a private balcony for sunrise coffee.',
 'Free Wi-Fi|Air conditioning|Private bathroom|Balcony|Ocean view|Breakfast',
 28500.00, 2, 'assets/images/ocean-suite.svg', 'active'),
('Deluxe Garden Room', 'Double',
 'A cool garden-facing room with tropical textures, a comfortable king bed and direct access to the villa courtyard.',
 'Free Wi-Fi|Air conditioning|Private bathroom|Garden view|Swimming pool|Breakfast',
 18500.00, 2, 'assets/images/garden-room.svg', 'active'),
('Tropical Family Room', 'Family',
 'A spacious two-room retreat for families, designed with natural timber, warm fabrics and easy access to the pool.',
 'Free Wi-Fi|Air conditioning|Private bathroom|Family area|Swimming pool|Parking|Breakfast',
 36500.00, 4, 'assets/images/family-room.svg', 'active');
