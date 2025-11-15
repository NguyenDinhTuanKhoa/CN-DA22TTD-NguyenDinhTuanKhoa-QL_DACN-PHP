<?php
class Topic {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT t.*, u.full_name as teacher_name 
            FROM topics t 
            JOIN users u ON t.teacher_id = u.user_id 
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllWithTeacher() {
        $stmt = $this->db->query("
            SELECT t.*, u.full_name as teacher_name, u.email as teacher_email 
            FROM topics t 
            JOIN users u ON t.teacher_id = u.user_id 
            ORDER BY t.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAvailableTopics() {
        $stmt = $this->db->query("
            SELECT t.*, u.full_name as teacher_name 
            FROM topics t 
            JOIN users u ON t.teacher_id = u.user_id 
            WHERE t.status = 'approved' AND t.current_students < t.max_students
            ORDER BY t.created_at DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function countAll() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM topics");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getStatistics() {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_topics,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(current_students) as total_registrations
            FROM topics
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByTeacherId($teacherId) {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE teacher_id = ? ORDER BY created_at DESC");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO topics (teacher_id, title, description, requirements, max_students) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['teacher_id'],
            $data['title'],
            $data['description'],
            $data['requirements'],
            $data['max_students'] ?? 1
        ]);
    }
    
    public function update($topicId, $data) {
        $stmt = $this->db->prepare("UPDATE topics SET title = ?, description = ?, requirements = ?, max_students = ? WHERE topic_id = ?");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['requirements'],
            $data['max_students'],
            $topicId
        ]);
    }
    
    public function delete($topicId) {
        $stmt = $this->db->prepare("DELETE FROM topics WHERE topic_id = ?");
        return $stmt->execute([$topicId]);
    }
    
    public function getById($topicId) {
        $stmt = $this->db->prepare("SELECT * FROM topics WHERE topic_id = ?");
        $stmt->execute([$topicId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function decreaseStudentCount($topicId) {
        $stmt = $this->db->prepare("
            UPDATE topics 
            SET current_students = GREATEST(current_students - 1, 0) 
            WHERE topic_id = ?
        ");
        return $stmt->execute([$topicId]);
    }
}
