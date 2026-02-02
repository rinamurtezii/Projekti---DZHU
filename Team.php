<?php
require_once 'DataBase.php';

class Team extends DataBase {
    private $conn;
    private $table = 'team';

    public function __construct() {
        $db = new DataBase();
        $this->conn = $db->startConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMember($name, $role, $image) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (name, role, image, created_at)
            VALUES (:name, :role, :image, NOW())
        ");
        return $stmt->execute([
            ':name' => $name,
            ':role' => $role,
            ':image' => $image
        ]);
    }

    public function updateMember($id, $name, $role, $image) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} SET
                name = :name,
                role = :role,
                image = :image,
                updated_at = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':name' => $name,
            ':role' => $role,
            ':image' => $image,
            ':id' => $id
        ]);
    }

    public function deleteMember($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function find($id){
        return $this->getById($id);
    }
}
?>

