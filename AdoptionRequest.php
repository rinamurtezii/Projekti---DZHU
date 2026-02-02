<?php
class AdoptionRequest {
  private PDO $conn;
  private string $table = "adoption_requests";

  public function __construct(PDO $conn){
    $this->conn = $conn;
  }

  public function getAllByUser(int $userId): array {
    $sql = "
      SELECT ar.*, d.name AS dog_name, d.image AS dog_image
      FROM {$this->table} ar
      JOIN dogs d ON d.id = ar.dog_id
      WHERE ar.user_id = :uid
      ORDER BY ar.id DESC
    ";
    $st = $this->conn->prepare($sql);
    $st->execute([':uid' => $userId]);
    return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
  }

  public function getByIdForUser(int $id, int $userId): ?array {
    $sql = "
      SELECT ar.*, d.name AS dog_name, d.image AS dog_image
      FROM {$this->table} ar
      JOIN dogs d ON d.id = ar.dog_id
      WHERE ar.id = :id AND ar.user_id = :uid
      LIMIT 1
    ";
    $st = $this->conn->prepare($sql);
    $st->execute([':id' => $id, ':uid' => $userId]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
  }

  public function updatePending(int $id, int $userId, string $full_name, string $phone, string $address, string $email, string $reason): bool {
    $sql = "
      UPDATE {$this->table}
      SET full_name = :full_name,
          phone = :phone,
          address = :address,
          email = :email,
          reason = :reason
      WHERE id = :id AND user_id = :uid AND status = 'pending'
    ";
    $st = $this->conn->prepare($sql);
    return $st->execute([
      ':full_name' => $full_name,
      ':phone' => $phone,
      ':address' => $address,
      ':email' => $email,
      ':reason' => $reason,
      ':id' => $id,
      ':uid' => $userId
    ]);
  }

 public function cancelPending(int $id, int $userId): bool {
  $sql = "UPDATE {$this->table}
          SET status='cancelled'
          WHERE id=:id AND user_id=:uid AND status='pending'
          LIMIT 1";
  $st = $this->conn->prepare($sql);
  return $st->execute([':id' => $id, ':uid' => $userId]);
}


public function getAll(): array {
    $sql = "
        SELECT ar.*, d.name AS dog_name, d.image AS dog_image, u.name AS user_name
        FROM {$this->table} ar
        LEFT JOIN dogs d ON d.id = ar.dog_id
        LEFT JOIN users u ON u.id = ar.user_id
        ORDER BY ar.created_at DESC
    ";
    return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

public function getById(int $id): ?array {
    $sql = "SELECT * FROM {$this->table} WHERE id=:id LIMIT 1";
    $st = $this->conn->prepare($sql);
    $st->execute([':id'=>$id]);
    return $st->fetch(PDO::FETCH_ASSOC) ?: null;
}

public function updateStatus(int $id, string $status): bool {
    $sql = "UPDATE {$this->table} SET status=:status WHERE id=:id";
    $st = $this->conn->prepare($sql);
    return $st->execute([':status'=>$status, ':id'=>$id]);
}

public function delete(int $id): bool {
    $sql = "DELETE FROM {$this->table} WHERE id=:id";
    $st = $this->conn->prepare($sql);
    return $st->execute([':id'=>$id]);
}
}
?>