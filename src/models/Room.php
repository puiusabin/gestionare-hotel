<?php

require_once __DIR__ . '/../config/database.php';

class Room
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findAll()
    {
        $query = "SELECT * FROM rooms ORDER BY room_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM rooms WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findAvailable()
    {
        $query = "SELECT * FROM rooms WHERE is_available = 1 ORDER BY room_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByType($type)
    {
        $query = "SELECT * FROM rooms WHERE room_type = :type ORDER BY room_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($roomNumber, $roomType, $capacity, $pricePerNight, $description = null, $imageUrl = null, $isAvailable = 1)
    {
        $query = "INSERT INTO rooms (room_number, room_type, capacity, price_per_night, description, image_url, is_available)
                  VALUES (:room_number, :room_type, :capacity, :price_per_night, :description, :image_url, :is_available)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_number', $roomNumber);
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':price_per_night', $pricePerNight);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':is_available', $isAvailable);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $roomNumber, $roomType, $capacity, $pricePerNight, $description = null, $imageUrl = null, $isAvailable = 1)
    {
        $query = "UPDATE rooms SET
                    room_number = :room_number,
                    room_type = :room_type,
                    capacity = :capacity,
                    price_per_night = :price_per_night,
                    description = :description,
                    image_url = :image_url,
                    is_available = :is_available
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':room_number', $roomNumber);
        $stmt->bindParam(':room_type', $roomType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':price_per_night', $pricePerNight);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':is_available', $isAvailable);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM rooms WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
