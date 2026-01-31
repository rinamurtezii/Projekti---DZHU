<?php
require_once 'DataBase.php';

class Team extends DataBase {

    private $conn;

    public function __construct() {
        $this->conn = $this->startConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM team");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

