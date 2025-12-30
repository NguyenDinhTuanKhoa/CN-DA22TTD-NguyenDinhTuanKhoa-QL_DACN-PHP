<?php
require_once '../app/models/Chat.php';

class ChatController extends Controller {
    private $chatModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->chatModel = new Chat();
    }
    
    // Trang chat - redirect thẳng vào phòng chat
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        // Lấy nhóm chat của user
        $chatGroup = $this->chatModel->getUserChatGroup($userId, $userRole);
        
        if ($chatGroup) {
            // Có nhóm chat -> vào phòng chat luôn
            header('Location: ' . BASE_URL . '/chat/room/' . $chatGroup['group_id']);
            exit;
        }
        
        // Chưa có nhóm chat -> hiển thị thông báo
        $data = [
            'title' => 'Tin nhắn',
            'message' => $userRole === 'teacher' 
                ? 'Chưa có sinh viên nào được duyệt. Nhóm chat sẽ được tạo khi bạn duyệt sinh viên đầu tiên.'
                : 'Bạn chưa được duyệt vào đề tài nào. Nhóm chat sẽ xuất hiện khi giáo viên duyệt đăng ký của bạn.'
        ];
        
        if ($userRole === 'student') {
            $this->view('student/chat/no_group', $data);
        } else {
            $this->view('teacher/chat/no_group', $data);
        }
    }
    
    // Phòng chat
    public function room($groupId) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        // Kiểm tra quyền truy cập
        if (!$this->chatModel->hasGroupAccess($groupId, $userId)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập nhóm chat này!';
            header('Location: ' . BASE_URL . '/' . $userRole);
            exit;
        }
        
        // Lấy thông tin nhóm
        $groupInfo = $this->chatModel->getGroupInfo($groupId);
        
        // Lấy danh sách thành viên
        $members = $this->chatModel->getGroupMembers($groupId);
        
        // Lấy tin nhắn
        $messages = $this->chatModel->getGroupMessages($groupId);
        
        $data = [
            'title' => 'Chat - ' . $groupInfo['group_name'],
            'groupInfo' => $groupInfo,
            'members' => $members,
            'messages' => $messages,
            'groupId' => $groupId
        ];
        
        if ($userRole === 'student') {
            $this->view('student/chat/room', $data);
        } else {
            $this->view('teacher/chat/room', $data);
        }
    }
    
    // API: Gửi tin nhắn
    public function sendMessage() {
        // Đảm bảo không có output trước JSON
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        $input = json_decode(file_get_contents('php://input'), true);
        $groupId = $input['group_id'] ?? null;
        $messageText = trim($input['message'] ?? '');
        
        if (!$groupId || empty($messageText)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ', 'debug' => ['groupId' => $groupId, 'message' => $messageText]]);
            exit;
        }
        
        // Kiểm tra quyền
        $hasAccess = $this->chatModel->hasGroupAccess($groupId, $userId);
        if (!$hasAccess) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập', 'debug' => ['groupId' => $groupId, 'userId' => $userId]]);
            exit;
        }
        
        // Gửi tin nhắn
        $messageId = $this->chatModel->sendMessage($groupId, $userId, $messageText);
        
        if ($messageId) {
            echo json_encode([
                'success' => true,
                'message_id' => $messageId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi gửi tin nhắn']);
        }
        exit;
    }
    
    // API: Lấy tin nhắn mới
    public function getMessages($groupId) {
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Kiểm tra quyền
        if (!$this->chatModel->hasGroupAccess($groupId, $userId)) {
            echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập']);
            exit;
        }
        
        $messages = $this->chatModel->getGroupMessages($groupId);
        
        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
        exit;
    }
}
