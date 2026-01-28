<?php
class DataBase {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "pawcare";

    public function startConnection() {
        try {
            $conn = new PDO(
                "mysql:host=$this->server;dbname=$this->database;charset=utf8mb4",
                $this->username,
                $this->password
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
}
?>
