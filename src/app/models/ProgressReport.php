<?php
class ProgressReport {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByRegistrationId($registrationId) {
        $stmt = $this->db->prepare("
            SELECT * FROM progress_reports 
            WHERE registration_id = ? 
            ORDER BY week_number ASC, created_at DESC
        ");
        $stmt->execute([$registrationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO progress_reports (registration_id, week_number, task_name, note, status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['registration_id'],
            $data['week_number'],
            $data['task_name'],
            $data['description'] ?? null,
            $data['status']
        ]);
    }
    
    public function update($reportId, $data) {
        $stmt = $this->db->prepare("
            UPDATE progress_reports 
            SET task_name = ?, note = ?, status = ? 
            WHERE report_id = ?
        ");
        return $stmt->execute([
            $data['task_name'],
            $data['description'] ?? null,
            $data['status'],
            $reportId
        ]);
    }
    
    public function delete($reportId) {
        $stmt = $this->db->prepare("DELETE FROM progress_reports WHERE report_id = ?");
        return $stmt->execute([$reportId]);
    }
}
