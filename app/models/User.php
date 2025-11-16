<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT user_id, username, full_name, email, role, student_code, phone, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT user_id, username, full_name, email, role, student_code, phone, created_at FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, password, full_name, email, role, student_code, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['full_name'],
                $data['email'],
                $data['role'],
                $data['student_code'] ?? null,
                $data['phone'] ?? null
            ]);
            return $result;
        } catch (PDOException $e) {
            // Log lỗi để debug
            error_log("Error creating user: " . $e->getMessage());
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            return false;
        }
    }
    
    public function update($id, $data) {
        // Nếu có password mới, cập nhật cả password
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET full_name = ?, email = ?, role = ?, student_code = ?, phone = ?, password = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $data['role'],
                $data['student_code'] ?? null,
                $data['phone'] ?? null,
                password_hash($data['password'], PASSWORD_DEFAULT),
                $id
            ]);
        } else {
            // Không đổi password
            $sql = "UPDATE users SET full_name = ?, email = ?, role = ?, student_code = ?, phone = ? WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $data['full_name'],
                $data['email'],
                $data['role'],
                $data['student_code'] ?? null,
                $data['phone'] ?? null,
                $id
            ]);
        }
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countByRole($role) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function search($keyword) {
        if (empty($keyword)) {
            return $this->getAll();
        }
        
        $stmt = $this->db->prepare("
            SELECT user_id, username, full_name, email, role, student_code, phone, created_at 
            FROM users 
            WHERE username LIKE ? 
               OR full_name LIKE ? 
               OR email LIKE ? 
               OR student_code LIKE ?
            ORDER BY created_at DESC
        ");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchByRole($role, $keyword = '') {
        if (empty($keyword)) {
            $stmt = $this->db->prepare("
                SELECT user_id, username, full_name, email, role, student_code, phone, created_at 
                FROM users 
                WHERE role = ?
                ORDER BY created_at DESC
            ");
            $stmt->execute([$role]);
        } else {
            $stmt = $this->db->prepare("
                SELECT user_id, username, full_name, email, role, student_code, phone, created_at 
                FROM users 
                WHERE role = ? 
                  AND (username LIKE ? 
                       OR full_name LIKE ? 
                       OR email LIKE ? 
                       OR student_code LIKE ?)
                ORDER BY created_at DESC
            ");
            $searchTerm = "%{$keyword}%";
            $stmt->execute([$role, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
