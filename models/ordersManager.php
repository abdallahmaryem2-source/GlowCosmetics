<?php
// models/ordersManager.php
class OrdersManager {
    public function __construct(private PDO $db) {}

    public function createOrder(int $userId, float $total): int {
        $sql  = "INSERT INTO commandes (user_id, order_date, total_amount, status)
                 VALUES (:uid, NOW(), :total, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':uid' => $userId, ':total' => $total]);
        return (int)$this->db->lastInsertId();
    }

    public function getAllOrders(): array {
        $sql = "SELECT c.*, u.username, u.email 
                FROM commandes c
                LEFT JOIN users u ON c.user_id = u.id
                ORDER BY c.order_date DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getOrdersByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM commandes WHERE user_id = :uid ORDER BY order_date DESC"
        );
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE commandes SET status=:status WHERE id=:id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function getTotalRevenue(): float {
        return (float)$this->db->query("SELECT SUM(total_amount) FROM commandes WHERE status != 'cancelled'")->fetchColumn();
    }

    public function getTotalOrders(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM commandes")->fetchColumn();
    }
}