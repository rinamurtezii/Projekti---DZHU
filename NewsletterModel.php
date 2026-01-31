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


public function getNewsById(int $id): ?array {
    $sql = "SELECT id, title, summary, content, image, read_time, created_at
            FROM news
            WHERE id = :id
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

public function getMoreStories(int $excludeId, int $limit = 3): array {
    $limit = max(1, (int)$limit);

    $sql = "SELECT id, title, summary, image
            FROM news
            WHERE id <> :excludeId
            ORDER BY created_at DESC
            LIMIT $limit";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":excludeId", $excludeId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
