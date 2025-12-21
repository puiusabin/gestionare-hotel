<?php

require_once __DIR__ . '/../config/database.php';

// User model handles user authentication and operations
class User
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create new user with hashed password, returns user ID on success
    public function create($email, $password, $firstName, $lastName, $phone = null, $role = 'guest')
    {
        $query = "INSERT INTO users (email, password, first_name, last_name, phone, role)
                  VALUES (:email, :password, :first_name, :last_name, :phone, :role)";

        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Find user by email address, returns user array or false
    public function findByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Find user by ID, returns user array or false
    public function findById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Verify plain password against hashed password
    public function verifyPassword($plainPassword, $hashedPassword)
    {
        return password_verify($plainPassword, $hashedPassword);
    }

    // Check if email is already registered
    public function emailExists($email)
    {
        $user = $this->findByEmail($email);
        return $user !== false;
    }
}
