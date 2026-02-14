<?php

require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/database.php';

// Special connection to create the database if it doesn't exist
$db = new Database();
$host = env('MYSQLHOST', '127.0.0.1');
$user = env('MYSQLUSER', 'root');
$pass = env('MYSQLPASSWORD', '');
$port = env('MYSQLPORT', '3306');
$dbName = env('MYSQLDATABASE', 'hotel_reservation');

try {
    // Connect to MySQL server without selecting a database
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to server. Ensuring database '$dbName' exists...<br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database ensures/created.<br>";
} catch (PDOException $e) {
    echo "Note: Could not ensure database exists via server connection: " . $e->getMessage() . "<br>";
    echo "Attempting to continue with default connection...<br>";
}

// Now use the standard connection
$conn = $db->getConnection();

echo "Starting table setup...<br>
";

// 1. Create Tables
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role ENUM('guest', 'admin') DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(50) NOT NULL UNIQUE,
    room_type ENUM('single', 'double', 'suite') NOT NULL,
    capacity INT NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    $conn->exec($sql);
    echo "Tables created successfully.
";

    // 2. Seed Users
    $users = [
        ['admin@hotel.com', password_hash('admin123', PASSWORD_DEFAULT), 'Admin', 'User', 'admin'],
        ['guest@example.com', password_hash('guest123', PASSWORD_DEFAULT), 'John', 'Doe', 'guest']
    ];

    $stmt = $conn->prepare("INSERT IGNORE INTO users (email, password, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)");
    foreach ($users as $user) {
        $stmt->execute($user);
    }
    echo "Users seeded.
";

    // 3. Seed Rooms
    $rooms = [
        ['101', 'single', 1, 100.00, 'Cosy single room for solo travelers'],
        ['102', 'single', 1, 110.00, 'Premium single room with city view'],
        ['201', 'double', 2, 180.00, 'Spacious double room with queen size bed'],
        ['202', 'double', 2, 190.00, 'Deluxe double room with balcony'],
        ['301', 'suite', 4, 350.00, 'Luxury suite with two bedrooms and living area'],
        ['302', 'suite', 3, 300.00, 'Junior suite with separate sitting area']
    ];

    $stmt = $conn->prepare("INSERT IGNORE INTO rooms (room_number, room_type, capacity, price_per_night, description) VALUES (?, ?, ?, ?, ?)");
    foreach ($rooms as $room) {
        $stmt->execute($room);
    }
    echo "Rooms seeded.
";

    echo "Database setup complete!
";

} catch (PDOException $e) {
    echo "Error setting up database: " . $e->getMessage() . "
";
}
