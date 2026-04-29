<?php
// models/favoriteManager.php
class FavoriteManager {
    public function __construct(private PDO $db) {}

    public function getFavoritesByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT p.* FROM favorites f
             JOIN products p ON f.product_id = p.id
             WHERE f.user_id = :uid
             ORDER BY f.created_at DESC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function getFavoriteIdsByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT product_id FROM favorites WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isFavorite(int $userId, int $productId): bool {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM favorites WHERE user_id = :uid AND product_id = :pid"
        );
        $stmt->execute([':uid' => $userId, ':pid' => $productId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function addFavorite(int $userId, int $productId): bool {
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO favorites (user_id, product_id, created_at)
             VALUES (:uid, :pid, NOW())"
        );
        return $stmt->execute([':uid' => $userId, ':pid' => $productId]);
    }

    public function removeFavorite(int $userId, int $productId): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM favorites WHERE user_id = :uid AND product_id = :pid"
        );
        return $stmt->execute([':uid' => $userId, ':pid' => $productId]);
    }

    public function countByUser(int $userId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    }
}
