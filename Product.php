<?php
require_once 'DataBase.php';

class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->startConnection();
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
     public function addProduct($title, $description, $price, $category, $image, $created_by) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} 
            (title, description, price, category, image, created_by, created_at) 
            VALUES (:title, :description, :price, :category, :image, :created_by, NOW())
        ");
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':category' => $category,
            ':image' => $image,
            ':created_by' => $created_by
        ]);
    }
    
    public function updateProduct($id, $title, $description, $price, $category, $image, $updated_by) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} SET 
                title=:title,
                description=:description,
                price=:price,
                category=:category,
                image=:image,
                updated_by=:updated_by,
                updated_at=NOW()
            WHERE id=:id
        ");
        return $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':category' => $category,
            ':image' => $image,
            ':updated_by' => $updated_by,
            ':id' => $id
        ]);
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=:id");
        return $stmt->execute([':id' => $id]);
    }
    public function find($id){
    return $this->getProductById($id);
}
}
?>
