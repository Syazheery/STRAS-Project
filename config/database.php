<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'honkai_shop'; // Your database name
    private $username = 'root';       // Default XAMPP username
    private $password = '';           // Empty password (default)
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // More detailed error message
            die("Database Connection Failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>