<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    private $conn = null;

    public function __construct()
    {
        // Prioritize Railway's MYSQL_URL if available
        $mysqlUrl = env('MYSQL_URL');
        if ($mysqlUrl) {
            $url = parse_url($mysqlUrl);
            $this->host = $url['host'] ?? '127.0.0.1';
            $this->db_name = isset($url['path']) ? ltrim($url['path'], '/') : 'hotel_reservation';
            $this->username = $url['user'] ?? 'root';
            $this->password = $url['pass'] ?? '';
            $this->port = $url['port'] ?? '3306';
        } else {
            // Fallback to individual Railway variables or local defaults
            // Use 127.0.0.1 instead of localhost to force TCP/IP over socket
            $this->host = env('MYSQLHOST', env('DB_HOST', '127.0.0.1'));
            $this->db_name = env('MYSQLDATABASE', env('DB_NAME', 'hotel_reservation'));
            $this->username = env('MYSQLUSER', env('DB_USER', 'root'));
            $this->password = env('MYSQLPASSWORD', env('DB_PASS', ''));
            $this->port = env('MYSQLPORT', '3306');
        }
    }

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                // Force TCP connection by specifying the host and port
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4";
                
                $this->conn = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());

                if (env('APP_ENV') === 'production') {
                    die("Database connection error. Please contact support.");
                } else {
                    die("Connection failed: " . $e->getMessage() . " (Host: {$this->host}, Port: {$this->port})");
                }
            }
        }
        return $this->conn;
    }
}
