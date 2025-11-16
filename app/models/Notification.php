<?php
class Notification {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByUserId($userId, $limit = null) {
        $sql = "
            SELECT n.*, u.full_name as sender_name 
            FROM notifications n
            LEFT JOIN users u ON n.sender_id = u.user_id
            WHERE n.receiver_id = ? 
            ORDER BY n.created_at DESC
        ";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($senderId, $receiverId, $title, $content) {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (sender_id, receiver_id, title, content) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$senderId, $receiverId, $title, $content]);
    }
    
    public function markAsRead($notificationId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
        return $stmt->execute([$notificationId]);
    }
    
    public function delete($notificationId) {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE notification_id = ?");
        return $stmt->execute([$notificationId]);
    }
    
    public function sendToStudent($senderId, $studentId, $title, $message) {
        return $this->create($senderId, $studentId, $title, $message);
    }
}
