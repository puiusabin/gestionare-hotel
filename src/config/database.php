<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn = null;

    public function __construct()
    {
        // Prioritize Railway environment variables, fallback to defaults
        $this->host = env('MYSQLHOST', 'localhost');
        $this->db_name = env('MYSQLDATABASE', 'hotel_reservation');
        $this->username = env('MYSQLUSER', 'root');
        $this->password = env('MYSQLPASSWORD', '');
        
        $mysqlUrl = env('MYSQL_URL');
        if ($mysqlUrl) {
            $url = parse_url($mysqlUrl);
            $this->host = $url['host'];
            $this->db_name = ltrim($url['path'], '/');
            $this->username = $url['user'];
            $this->password = $url['pass'];
        }
    }

    public function getConnection()
    {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                if (env('MYSQLPORT')) {
                    $dsn .= ";port=" . env('MYSQLPORT');
                }
                
                $this->conn = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
