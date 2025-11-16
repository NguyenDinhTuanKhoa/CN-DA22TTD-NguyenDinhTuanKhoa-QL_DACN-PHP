<?php
class Registration {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByTeacherId($teacherId) {
        $stmt = $this->db->prepare("
            SELECT r.*, t.title as topic_title, u.full_name as student_name, u.email as student_email, u.student_code
            FROM registrations r
            JOIN topics t ON r.topic_id = t.topic_id
            JOIN users u ON r.student_id = u.user_id
            WHERE t.teacher_id = ?
            ORDER BY r.registered_at DESC
        ");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByRegistrationId($regId) {
        $stmt = $this->db->prepare("
            SELECT r.*, t.title as topic_title, u.full_name as student_name, u.email as student_email, u.student_code
            FROM registrations r
            JOIN topics t ON r.topic_id = t.topic_id
            JOIN users u ON r.student_id = u.user_id
            WHERE r.registration_id = ?
        ");
        $stmt->execute([$regId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($regId, $status) {
        $stmt = $this->db->prepare("UPDATE registrations SET status = ? WHERE registration_id = ?");
        return $stmt->execute([$status, $regId]);
    }
    
    public function deleteRegistration($regId) {
        $stmt = $this->db->prepare("DELETE FROM registrations WHERE registration_id = ?");
        return $stmt->execute([$regId]);
    }
    
    public function getAll() {
        $stmt = $this->db->query("
            SELECT r.*, t.title as topic_title, u.full_name as student_name, u.email as student_email, u.student_code,
                   ut.full_name as teacher_name
            FROM registrations r
            JOIN topics t ON r.topic_id = t.topic_id
            JOIN users u ON r.student_id = u.user_id
            JOIN users ut ON t.teacher_id = ut.user_id
            ORDER BY r.registered_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByStudentId($studentId) {
        $stmt = $this->db->prepare("
            SELECT r.registration_id, r.student_id, r.topic_id, r.status, 
                   r.registered_at as created_at, r.approved_at,
                   t.title as topic_title, t.description, t.requirements,
                   u.full_name as teacher_name, u.email as teacher_email
            FROM registrations r
            JOIN topics t ON r.topic_id = t.topic_id
            JOIN users u ON t.teacher_id = u.user_id
            WHERE r.student_id = ?
            ORDER BY r.registered_at DESC
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function register($studentId, $topicId) {
        try {
            // Kiểm tra xem sinh viên đã đăng ký đề tài nào chưa
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM registrations WHERE student_id = ?");
            $stmt->execute([$studentId]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                return false; // Đã đăng ký rồi
            }
            
            // Kiểm tra số lượng sinh viên đã đăng ký
            $stmt = $this->db->prepare("SELECT current_students, max_students FROM topics WHERE topic_id = ?");
            $stmt->execute([$topicId]);
            $topic = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($topic['current_students'] >= $topic['max_students']) {
                return false; // Đã đủ số lượng
            }
            
            // Đăng ký
            $stmt = $this->db->prepare("INSERT INTO registrations (student_id, topic_id, status) VALUES (?, ?, 'pending')");
            $result = $stmt->execute([$studentId, $topicId]);
            
            if ($result) {
                // Cập nhật số lượng sinh viên
                $stmt = $this->db->prepare("UPDATE topics SET current_students = current_students + 1 WHERE topic_id = ?");
                $stmt->execute([$topicId]);
            }
            
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
}