<?php
class TimeSetting {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM time_settings ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM time_settings WHERE is_active = 1 AND NOW() BETWEEN start_date AND end_date");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO time_settings (setting_name, start_date, end_date, description, is_active) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['setting_name'],
            $data['start_date'],
            $data['end_date'],
            $data['description'] ?? null,
            $data['is_active'] ?? 1
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE time_settings SET setting_name = ?, start_date = ?, end_date = ?, description = ?, is_active = ? WHERE setting_id = ?");
        return $stmt->execute([
            $data['setting_name'],
            $data['start_date'],
            $data['end_date'],
            $data['description'] ?? null,
            $data['is_active'] ?? 1,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM time_settings WHERE setting_id = ?");
        return $stmt->execute([$id]);
    }
}
