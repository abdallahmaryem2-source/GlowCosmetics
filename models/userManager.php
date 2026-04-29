<?php
// models/userManager.php
class UserManager {
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function register(string $username, string $email, string $password): bool {
        if ($this->findByEmail($email)) return false;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password, role) VALUES (:u, :e, :p, 'client')"
        );
        return $stmt->execute([':u' => $username, ':e' => $email, ':p' => $hash]);
    }

    public function login(string $email, string $password): ?array {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) return $user;
        return null;
    }

    public function getTotalUsers(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}