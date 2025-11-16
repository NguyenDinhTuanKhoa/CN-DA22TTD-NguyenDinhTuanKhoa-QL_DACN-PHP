<?php
class AdminController extends Controller {
    
    public function __construct() {
        // Debug: Log mọi request đến AdminController
        $logFile = __DIR__ . '/../../debug.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " AdminController called - Method: " . $_SERVER['REQUEST_METHOD'] . " - URI: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);
    }
    
    public function test() {
        echo "<h1>TEST ENDPOINT WORKS!</h1>";
        echo "<p>AdminController::test() được gọi thành công</p>";
        exit;
    }
    
    private function checkAdminSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /PHP-CN/public/login');
            exit;
        }
        return $_SESSION['user_id'];
    }
    
    public function index() {
        $this->checkAdminSession();
        $userModel = $this->model('User');
        $topicModel = $this->model('Topic');
        
        $data = [
            'title' => 'Trang quản trị',
            'total_students' => $userModel->countByRole('student'),
            'total_teachers' => $userModel->countByRole('teacher'),
            'total_topics' => $topicModel->countAll(),
            'topic_stats' => $topicModel->getStatistics()
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    public function users() {
        $this->checkAdminSession();
        $userModel = $this->model('User');
        
        // Lấy tham số tìm kiếm
        $role = $_GET['role'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        // Lấy danh sách người dùng theo bộ lọc
        if ($role !== 'all') {
            $users = $userModel->searchByRole($role, $search);
        } else {
            $users = $userModel->search($search);
        }
        
        $data = [
            'title' => 'Quản lý người dùng',
            'users' => $users,
            'current_role' => $role,
            'current_search' => $search,
            'total_students' => $userModel->countByRole('student'),
            'total_teachers' => $userModel->countByRole('teacher'),
            'total_admins' => $userModel->countByRole('admin')
        ];
        $this->view('admin/users', $data);
    }
    
    public function createUser() {
        $this->checkAdminSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // DEBUG: Log để biết có vào đây không
            $logFile = __DIR__ . '/../../debug.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . " === AdminController::createUser POST ===\n", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
            
            $userModel = $this->model('User');
            
            // Đơn giản hóa - chỉ tạo user, không validate phức tạp
            try {
                $result = $userModel->create($_POST);
                file_put_contents($logFile, date('Y-m-d H:i:s') . " Create result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n", FILE_APPEND);
                
                if ($result) {
                    $_SESSION['success'] = 'Thêm người dùng thành công!';
                } else {
                    $_SESSION['error'] = isset($_SESSION['error']) ? $_SESSION['error'] : 'Có lỗi xảy ra khi thêm người dùng!';
                }
            } catch (Exception $e) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " Exception: " . $e->getMessage() . "\n", FILE_APPEND);
                $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
            }
            
            header('Location: /PHP-CN/public/admin/users');
            exit;
        }
        
        $data = [
            'title' => 'Thêm người dùng mới',
            'action' => 'create'
        ];
        $this->view('admin/user_form', $data);
    }
    
    public function editUser($id) {
        $this->checkAdminSession();
        $userModel = $this->model('User');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $userModel->update($id, $_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật người dùng thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
            }
            
            header('Location: /PHP-CN/public/admin/users');
            exit;
        }
        
        $data = [
            'title' => 'Chỉnh sửa người dùng',
            'user' => $userModel->getById($id),
            'action' => 'edit'
        ];
        $this->view('admin/user_form', $data);
    }
    
    public function deleteUser($id) {
        $this->checkAdminSession();
        $userModel = $this->model('User');
        
        $result = $userModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa người dùng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa người dùng!';
        }
        
        header('Location: /PHP-CN/public/admin/users');
        exit;
    }
    
    public function timeSettings() {
        $this->checkAdminSession();
        $timeModel = $this->model('TimeSetting');
        
        // Tự động khóa các chức năng hết hạn (tạm thời bỏ qua để tránh lỗi)
        // $timeModel->autoLockExpired();
        
        $data = [
            'title' => 'Cài đặt thời gian',
            'settings' => $timeModel->getAll()
        ];
        
        $this->view('admin/time_settings', $data);
    }
    
    public function createTimeSetting() {
        $this->checkAdminSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $timeModel = $this->model('TimeSetting');
            $result = $timeModel->create($_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Thêm cài đặt thời gian thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra!';
            }
            
            header('Location: /PHP-CN/public/admin/timeSettings');
            exit;
        }
    }
    
    public function editTimeSetting($id) {
        $this->checkAdminSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $timeModel = $this->model('TimeSetting');
            $result = $timeModel->update($id, $_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra!';
            }
            
            header('Location: /PHP-CN/public/admin/timeSettings');
            exit;
        }
    }
    
    public function toggleTimeSetting($id) {
        $this->checkAdminSession();
        $timeModel = $this->model('TimeSetting');
        
        $result = $timeModel->toggleActive($id);
        
        if ($result) {
            $_SESSION['success'] = 'Đã thay đổi trạng thái!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: /PHP-CN/public/admin/timeSettings');
        exit;
    }
    
    public function deleteTimeSetting($id) {
        $this->checkAdminSession();
        $timeModel = $this->model('TimeSetting');
        
        $result = $timeModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: /PHP-CN/public/admin/timeSettings');
        exit;
    }
    
    public function topics() {
        $this->checkAdminSession();
        $topicModel = $this->model('Topic');
        $data = [
            'title' => 'Quản lý đề tài',
            'topics' => $topicModel->getAll()
        ];
        $this->view('admin/topics', $data);
    }
    
    public function submissions() {
        $this->checkAdminSession();
        $submissionModel = $this->model('Submission');
        
        $data = [
            'title' => 'Tổng hợp bài nộp',
            'submissions' => $submissionModel->getAllWithDetails()
        ];
        
        $this->view('admin/submissions', $data);
    }
    
    public function approveTopic($topicId) {
        $this->checkAdminSession();
        $topicModel = $this->model('Topic');
        
        $result = $topicModel->updateStatus($topicId, 'approved');
        
        if ($result) {
            $_SESSION['success'] = 'Đã duyệt đề tài thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi duyệt đề tài!';
        }
        
        header('Location: /PHP-CN/public/admin/topics');
        exit;
    }
    
    public function rejectTopic($topicId) {
        $this->checkAdminSession();
        $topicModel = $this->model('Topic');
        
        $result = $topicModel->updateStatus($topicId, 'rejected');
        
        if ($result) {
            $_SESSION['success'] = 'Đã từ chối đề tài!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: /PHP-CN/public/admin/topics');
        exit;
    }
}
