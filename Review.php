<?php 
require_once 'DataBase.php';

class Review{
    private $conn;
    private $table='reviews';

    public function __construct(){
        $db= new DataBase();
        $this->conn=$db->startConnection();
    }

    public function getAllReviews(){
         $stmt = $this->conn->prepare("SELECT r.*, u.name AS user_name, p.title AS product_name 
                                      FROM {$this->table} r
                                      JOIN users u ON r.user_id = u.id
                                      JOIN products p ON r.product_id = p.id
                                      ORDER BY r.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReviewsByProduct($product_id) {
        $stmt = $this->conn->prepare("SELECT r.*, u.name AS user_name
                                      FROM {$this->table} r
                                      JOIN users u ON r.user_id = u.id
                                      WHERE r.product_id = :product_id
                                      ORDER BY r.created_at DESC");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReview($product_id, $user_id, $rating, $comment) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (product_id, user_id, rating, comment, created_at) 
                                      VALUES (:product_id, :user_id, :rating, :comment, NOW())");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateReview($id, $rating, $comment, $updated_by) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
                                      SET rating = :rating, comment = :comment, updated_at = NOW(), updated_by = :updated_by
                                      WHERE id = :id");
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':updated_by', $updated_by, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteReview($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function find($id){
    $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>