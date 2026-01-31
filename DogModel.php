<?php
class DogModel {
    private PDO $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getDogs(): array {
    $sql = "SELECT id, name, description, image, age, energy, size, status, created_at
            FROM dogs
            WHERE status = 'available'
            ORDER BY created_at DESC";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

    public function getById(int $id): ?array {
    $stmt = $this->conn->prepare("SELECT * FROM dogs WHERE id=? LIMIT 1");
    $stmt->execute([$id]);
    $dog = $stmt->fetch(PDO::FETCH_ASSOC);
    return $dog ?: null;
}

}
