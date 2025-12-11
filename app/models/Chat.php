<?php
class Chat {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Tạo hoặc lấy nhóm chat của giáo viên
    public function getOrCreateTeacherGroup($teacherId) {
        try {
            // Kiểm tra nhóm đã tồn tại chưa
            $stmt = $this->db->prepare("SELECT group_id FROM chat_groups WHERE teacher_id = ?");
            $stmt->execute([$teacherId]);
            $group = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($group) {
                return $group['group_id'];
            }
            
            // Lấy tên giáo viên
            $stmt = $this->db->prepare("SELECT full_name FROM users WHERE user_id = ?");
            $stmt->execute([$teacherId]);
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Tạo nhóm mới
            $groupName = "Nhóm hướng dẫn - " . $teacher['full_name'];
            $stmt = $this->db->prepare("INSERT INTO chat_groups (teacher_id, group_name) VALUES (?, ?)");
            $stmt->execute([$teacherId, $groupName]);
            $groupId = $this->db->lastInsertId();
            
            // Thêm giáo viên vào nhóm
            $this->addMemberToGroup($groupId, $teacherId);
            
            return $groupId;
            
        } catch (PDOException $e) {
            error_log("Error creating teacher group: " . $e->getMessage());
            return null;
        }
    }
    
    // Thêm sinh viên vào nhóm chat khi được duyệt
    public function addStudentToTeacherGroup($teacherId, $studentId) {
        try {
            $groupId = $this->getOrCreateTeacherGroup($teacherId);
            if ($groupId) {
                $this->addMemberToGroup($groupId, $studentId);
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error adding student to group: " . $e->getMessage());
            return false;
        }
    }
    
    // Thêm thành viên vào nhóm
    private function addMemberToGroup($groupId, $userId) {
        try {
            $stmt = $this->db->prepare("
                INSERT IGNORE INTO chat_group_members (group_id, user_id) VALUES (?, ?)
            ");
            return $stmt->execute([$groupId, $userId]);
        } catch (PDOException $e) {
            error_log("Error adding member: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy nhóm chat của user
    public function getUserChatGroup($userId, $userRole) {
        try {
            if ($userRole === 'teacher') {
                // Giáo viên: lấy nhóm của mình
                $stmt = $this->db->prepare("
                    SELECT 
                        cg.group_id,
                        cg.group_name,
                        cg.teacher_id,
                        (SELECT COUNT(*) FROM chat_group_members WHERE group_id = cg.group_id) - 1 as student_count,
                        (SELECT message_text FROM chat_messages WHERE group_id = cg.group_id ORDER BY created_at DESC LIMIT 1) as last_message,
                        (SELECT created_at FROM chat_messages WHERE group_id = cg.group_id ORDER BY created_at DESC LIMIT 1) as last_message_time
                    FROM chat_groups cg
                    WHERE cg.teacher_id = ?
                ");
                $stmt->execute([$userId]);
                
            } else {
                // Sinh viên: lấy nhóm mà mình là thành viên
                $stmt = $this->db->prepare("
                    SELECT 
                        cg.group_id,
                        cg.group_name,
                        cg.teacher_id,
                        u.full_name as teacher_name,
                        (SELECT COUNT(*) FROM chat_group_members WHERE group_id = cg.group_id) - 1 as student_count,
                        (SELECT message_text FROM chat_messages WHERE group_id = cg.group_id ORDER BY created_at DESC LIMIT 1) as last_message,
                        (SELECT created_at FROM chat_messages WHERE group_id = cg.group_id ORDER BY created_at DESC LIMIT 1) as last_message_time
                    FROM chat_groups cg
                    JOIN chat_group_members cgm ON cg.group_id = cgm.group_id
                    JOIN users u ON cg.teacher_id = u.user_id
                    WHERE cgm.user_id = ? AND cg.teacher_id != ?
                ");
                $stmt->execute([$userId, $userId]);
            }
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting chat group: " . $e->getMessage());
            return null;
        }
    }
    
    // Lấy danh sách thành viên trong nhóm
    public function getGroupMembers($groupId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    u.user_id,
                    u.full_name,
                    u.role,
                    u.student_code,
                    cgm.joined_at
                FROM chat_group_members cgm
                JOIN users u ON cgm.user_id = u.user_id
                WHERE cgm.group_id = ?
                ORDER BY u.role DESC, u.full_name ASC
            ");
            $stmt->execute([$groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting members: " . $e->getMessage());
            return [];
        }
    }
    
    // Lấy tin nhắn trong nhóm
    public function getGroupMessages($groupId, $limit = 100) {
        try {
            $groupId = intval($groupId); // Đảm bảo là số nguyên
            $stmt = $this->db->prepare("
                SELECT 
                    cm.message_id,
                    cm.message_text,
                    cm.message_type,
                    cm.file_url,
                    cm.created_at,
                    u.user_id,
                    u.full_name,
                    u.role
                FROM chat_messages cm
                JOIN users u ON cm.sender_id = u.user_id
                WHERE cm.group_id = :group_id
                ORDER BY cm.created_at ASC
                LIMIT 100
            ");
            $stmt->bindParam(':group_id', $groupId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting messages: " . $e->getMessage());
            return [];
        }
    }
    
    // Gửi tin nhắn
    public function sendMessage($groupId, $senderId, $messageText, $messageType = 'text', $fileUrl = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO chat_messages (group_id, sender_id, message_text, message_type, file_url) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $result = $stmt->execute([$groupId, $senderId, $messageText, $messageType, $fileUrl]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
            
        } catch (PDOException $e) {
            error_log("Error sending message: " . $e->getMessage());
            return false;
        }
    }
    
    // Kiểm tra quyền truy cập nhóm chat
    public function hasGroupAccess($groupId, $userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count
                FROM chat_group_members
                WHERE group_id = ? AND user_id = ?
            ");
            $stmt->execute([$groupId, $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
            
        } catch (PDOException $e) {
            error_log("Error checking access: " . $e->getMessage());
            return false;
        }
    }
    
    // Lấy thông tin nhóm chat
    public function getGroupInfo($groupId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    cg.group_id,
                    cg.group_name,
                    cg.teacher_id,
                    u.full_name as teacher_name
                FROM chat_groups cg
                JOIN users u ON cg.teacher_id = u.user_id
                WHERE cg.group_id = ?
            ");
            $stmt->execute([$groupId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error getting group info: " . $e->getMessage());
            return null;
        }
    }
}
