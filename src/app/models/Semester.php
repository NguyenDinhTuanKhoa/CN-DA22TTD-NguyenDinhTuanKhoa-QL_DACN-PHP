<?php
class Semester {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM semesters ORDER BY is_active DESC, start_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM semesters WHERE semester_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM semesters WHERE is_active = 1 LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        if (!empty($data['is_active'])) {
            $this->deactivateAll();
        }
        
        $stmt = $this->db->prepare("
            INSERT INTO semesters (name, academic_year, semester_number, start_date, end_date, is_active) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['academic_year'],
            $data['semester_number'],
            $data['start_date'],
            $data['end_date'],
            $data['is_active'] ?? 0
        ]);
    }
    
    public function update($id, $data) {
        if (!empty($data['is_active'])) {
            $this->deactivateAll();
        }
        
        $stmt = $this->db->prepare("
            UPDATE semesters SET name = ?, academic_year = ?, semester_number = ?, 
                start_date = ?, end_date = ?, is_active = ?
            WHERE semester_id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['academic_year'],
            $data['semester_number'],
            $data['start_date'],
            $data['end_date'],
            $data['is_active'] ?? 0,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM semesters WHERE semester_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function activate($id) {
        $this->deactivateAll();
        $stmt = $this->db->prepare("UPDATE semesters SET is_active = 1 WHERE semester_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function deactivateAll() {
        $this->db->query("UPDATE semesters SET is_active = 0");
    }
    
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS semesters (
            semester_id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            academic_year VARCHAR(20) NOT NULL,
            semester_number TINYINT NOT NULL DEFAULT 1,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            is_active TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        return $this->db->exec($sql);
    }
}
