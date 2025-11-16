<?php
class TimeSetting {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM time_settings ORDER BY setting_type, created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByType($type) {
        $stmt = $this->db->prepare("SELECT * FROM time_settings WHERE setting_type = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getActive() {
        $stmt = $this->db->query("SELECT * FROM time_settings WHERE is_active = 1 AND NOW() BETWEEN start_date AND end_date");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Kiểm tra xem chức năng có được mở không
    public function isFeatureEnabled($type) {
        $stmt = $this->db->prepare("
            SELECT is_active, auto_lock, start_date, end_date 
            FROM time_settings 
            WHERE setting_type = ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$type]);
        $setting = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$setting) {
            return false;
        }
        
        // Nếu admin tắt thủ công
        if ($setting['is_active'] == 0) {
            return false;
        }
        
        // Nếu bật auto_lock, kiểm tra thời gian
        if ($setting['auto_lock'] == 1) {
            $now = date('Y-m-d H:i:s');
            return ($now >= $setting['start_date'] && $now <= $setting['end_date']);
        }
        
        // Nếu không auto_lock, chỉ cần is_active = 1
        return true;
    }
    
    // Tự động khóa các chức năng hết hạn
    public function autoLockExpired() {
        $stmt = $this->db->prepare("
            UPDATE time_settings 
            SET is_active = 0 
            WHERE auto_lock = 1 
            AND is_active = 1 
            AND NOW() > end_date
        ");
        return $stmt->execute();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO time_settings 
            (setting_name, setting_type, start_date, end_date, description, is_active, auto_lock) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['setting_name'],
            $data['setting_type'],
            $data['start_date'],
            $data['end_date'],
            $data['description'] ?? null,
            $data['is_active'] ?? 1,
            $data['auto_lock'] ?? 1
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE time_settings 
            SET setting_name = ?, setting_type = ?, start_date = ?, end_date = ?, 
                description = ?, is_active = ?, auto_lock = ? 
            WHERE setting_id = ?
        ");
        return $stmt->execute([
            $data['setting_name'],
            $data['setting_type'],
            $data['start_date'],
            $data['end_date'],
            $data['description'] ?? null,
            $data['is_active'] ?? 1,
            $data['auto_lock'] ?? 1,
            $id
        ]);
    }
    
    // Khóa/mở khóa chức năng
    public function toggleActive($id) {
        $stmt = $this->db->prepare("UPDATE time_settings SET is_active = NOT is_active WHERE setting_id = ?");
        return $stmt->execute([$id]);
    }
    
    // Gia hạn thời gian
    public function extendTime($id, $newEndDate) {
        $stmt = $this->db->prepare("UPDATE time_settings SET end_date = ?, is_active = 1 WHERE setting_id = ?");
        return $stmt->execute([$newEndDate, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM time_settings WHERE setting_id = ?");
        return $stmt->execute([$id]);
    }
}
