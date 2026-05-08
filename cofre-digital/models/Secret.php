<?php
/**
 * Model de Segredo
 */

require_once __DIR__ . '/../core/Database.php';

class Secret {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $title, $content) {
        $sql = "INSERT INTO secrets (user_id, title, content) VALUES (:user_id, :title, :content)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $userId,
            ':title' => $title,
            ':content' => $content
        ]);
    }

    public function listByUser($userId) {
        $sql = "SELECT id, title, created_at FROM secrets WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findById($id, $userId) {
        $sql = "SELECT * FROM secrets WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
        return $stmt->fetch();
    }
}
