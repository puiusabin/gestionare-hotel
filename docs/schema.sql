
-- Create database
CREATE DATABASE IF NOT EXISTS hotel_reservation;
USE hotel_reservation;

-- Table: users
-- Stores all system users 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Hashed using password_hash()',
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('guest', 'admin') DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: rooms
-- Stores hotel rooms available for reservation
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    room_type ENUM('single', 'double', 'suite') NOT NULL,
    capacity INT NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    INDEX idx_room_number (room_number),
    INDEX idx_room_type (room_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: reservations
-- Stores all reservations made by users
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT,
    INDEX idx_user_id (user_id),
    INDEX idx_room_id (room_id),
    INDEX idx_dates (room_id, check_in_date, check_out_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data for testing 

-- Insert admin user (password: admin123)
INSERT INTO users (email, password, first_name, last_name, phone, role) VALUES
('admin@hotel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', '1234567890', 'admin');

-- Insert sample guest user (password: guest123)
INSERT INTO users (email, password, first_name, last_name, phone, role) VALUES
('guest@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Doe', '0987654321', 'guest');

-- Insert sample rooms
INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, is_available) VALUES
('101', 'single', 1, 150.00, 'Camera single confortabila cu baie proprie', TRUE),
('102', 'single', 1, 150.00, 'Camera single confortabila cu vedere la gradina', TRUE),
('201', 'double', 2, 250.00, 'Camera dubla cu pat matrimonial si balcon', TRUE),
('202', 'double', 2, 250.00, 'Camera dubla cu doua paturi simple', TRUE),
('301', 'suite', 4, 500.00, 'Suite de lux cu living separat si jacuzzi', TRUE),
('302', 'suite', 4, 500.00, 'Suite premium cu terasa si vedere panoramica', TRUE);

-- Insert sample reservation
INSERT INTO reservations (user_id, room_id, check_in_date, check_out_date, total_price, status) VALUES
(2, 1, '2025-12-15', '2025-12-18', 450.00, 'confirmed');
