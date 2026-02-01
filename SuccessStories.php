<?php
require_once 'DataBase.php';

class SuccessStories extends DataBase {
    private $conn;

    public function __construct() {
        $this->conn = $this->startConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM success_stories ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
