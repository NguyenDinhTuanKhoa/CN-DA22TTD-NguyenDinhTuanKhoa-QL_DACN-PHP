<?php
class Submission {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getByRegistrationId($registrationId) {
        $stmt = $this->db->prepare("SELECT * FROM submissions WHERE registration_id = ? ORDER BY submitted_at DESC LIMIT 1");
        $stmt->execute([$registrationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO submissions (registration_id, google_drive_link, github_link, note) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['registration_id'],
            $data['google_drive_link'],
            $data['github_link'],
            $data['note'] ?? null
        ]);
    }
    
    public function update($submissionId, $data) {
        $stmt = $this->db->prepare("UPDATE submissions SET google_drive_link = ?, github_link = ?, note = ? WHERE submission_id = ?");
        return $stmt->execute([
            $data['google_drive_link'],
            $data['github_link'],
            $data['note'] ?? null,
            $submissionId
        ]);
    }
    
    public function createOrUpdate($data) {
        $existing = $this->getByRegistrationId($data['registration_id']);
        
        if ($existing) {
            $stmt = $this->db->prepare("
                UPDATE submissions 
                SET google_drive_link = ?, github_link = ?, note = ?, submitted_at = NOW() 
                WHERE registration_id = ?
            ");
            return $stmt->execute([
                $data['drive_link'],
                $data['github_link'],
                $data['notes'] ?? null,
                $data['registration_id']
            ]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO submissions (registration_id, google_drive_link, github_link, note) 
                VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([
                $data['registration_id'],
                $data['drive_link'],
                $data['github_link'],
                $data['notes'] ?? null
            ]);
        }
    }
}