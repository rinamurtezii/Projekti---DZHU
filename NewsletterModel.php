<?php
class NewsletterModel {
    private PDO $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getNews(int $isMain, int $limit): array {
        $sql = "SELECT * FROM news 
                WHERE is_main = :is_main
                ORDER BY created_at DESC
                LIMIT $limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":is_main", $isMain, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEvents(int $limit): array {
        $sql = "SELECT * FROM events 
                ORDER BY id DESC
                LIMIT $limit";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
