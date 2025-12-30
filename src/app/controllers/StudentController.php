<?php
class StudentController extends Controller {
    
    private function checkStudentSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: /PHP-CN/public/login');
            exit;
        }
        return $_SESSION['user_id'];
    }
    
    public function index() {
        $userId = $this->checkStudentSession();
        $topicModel = $this->model('Topic');
        $registrationModel = $this->model('Registration');
        $notificationModel = $this->model('Notification');
        
        $data = [
            'title' => 'Trang chủ sinh viên',
            'available_topics' => $topicModel->getAvailableTopics(),
            'my_registration' => $registrationModel->getByStudentId($userId),
            'notifications' => $notificationModel->getByUserId($userId, 5)
        ];
        
        $this->view('student/dashboard', $data);
    }
    
    public function topics() {
        $this->checkStudentSession();
        $topicModel = $this->model('Topic');
        $timeModel = $this->model('TimeSetting');
        
        $data = [
            'title' => 'Danh sách đề tài',
            'topics' => $topicModel->getAllWithTeacher(),
            'can_register' => $timeModel->isFeatureEnabled('topic_registration'),
            'registration_time' => $timeModel->getByType('topic_registration')
        ];
        
        $this->view('student/topics', $data);
    }
    
    public function register($topicId) {
        $userId = $this->checkStudentSession();
        
        // Kiểm tra thời gian được phép đăng ký
        $timeModel = $this->model('TimeSetting');
        if (!$timeModel->isFeatureEnabled('topic_registration')) {
            $_SESSION['error'] = 'Chức năng đăng ký đề tài hiện đang bị khóa hoặc chưa đến thời gian!';
            header('Location: /PHP-CN/public/student/topics');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registrationModel = $this->model('Registration');
            $result = $registrationModel->register($userId, $topicId);
            
            if ($result) {
                $_SESSION['success'] = 'Đăng ký đề tài thành công!';
            } else {
                $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
            }
            header('Location: /PHP-CN/public/student/topics');
            exit;
        }
    }
    
    public function progress() {
        $userId = $this->checkStudentSession();
        $progressModel = $this->model('ProgressReport');
        $registrationModel = $this->model('Registration');
        
        $registration = $registrationModel->getByStudentId($userId);
        
        $data = [
            'title' => 'Báo cáo tiến độ',
            'registration' => $registration,
            'progress_reports' => $registration ? $progressModel->getByRegistrationId($registration['registration_id']) : []
        ];
        
        $this->view('student/progress', $data);
    }
    
    public function addProgress() {
        $userId = $this->checkStudentSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $progressModel = $this->model('ProgressReport');
            $result = $progressModel->create($_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Thêm báo cáo tiến độ thành công!';
            } else {
                $_SESSION['error'] = 'Thêm báo cáo thất bại.';
            }
            header('Location: /PHP-CN/public/student/progress');
            exit;
        }
    }
    
    public function notifications() {
        $userId = $this->checkStudentSession();
        $notificationModel = $this->model('Notification');
        
        $data = [
            'title' => 'Thông báo',
            'notifications' => $notificationModel->getByUserId($userId)
        ];
        
        $this->view('student/notifications', $data);
    }
    
    public function submission() {
        $userId = $this->checkStudentSession();
        $submissionModel = $this->model('Submission');
        $registrationModel = $this->model('Registration');
        
        $registration = $registrationModel->getByStudentId($userId);
        
        $data = [
            'title' => 'Nộp bài đồ án',
            'registration' => $registration,
            'submission' => $registration ? $submissionModel->getByRegistrationId($registration['registration_id']) : null
        ];
        
        $this->view('student/submission', $data);
    }
    
    public function submitProject() {
        $userId = $this->checkStudentSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submissionModel = $this->model('Submission');
            $result = $submissionModel->createOrUpdate($_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Nộp bài thành công!';
            } else {
                $_SESSION['error'] = 'Nộp bài thất bại.';
            }
            header('Location: /PHP-CN/public/student/submission');
            exit;
        }
    }
    
    public function profile() {
        $userId = $this->checkStudentSession();
        $userModel = $this->model('User');
        
        $data = [
            'title' => 'Thông tin cá nhân',
            'user' => $userModel->getById($userId)
        ];
        
        $this->view('student/profile', $data);
    }
    
    public function updateProfile() {
        $userId = $this->checkStudentSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $result = $userModel->update($userId, $_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật thông tin thành công!';
                $_SESSION['full_name'] = $_POST['full_name'];
            } else {
                $_SESSION['error'] = 'Cập nhật thất bại.';
            }
            header('Location: /public/student/profile');
            exit;
        }
    }
    
    public function changePassword() {
        $userId = $this->checkStudentSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin!';
                header('Location: /public/student/profile');
                exit;
            }
            
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                header('Location: /public/student/profile');
                exit;
            }
            
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
                header('Location: /public/student/profile');
                exit;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->getById($userId);
            
            // Kiểm tra mật khẩu hiện tại (hỗ trợ cả plain text và hash)
            $passwordMatch = false;
            if ($currentPassword === $user['password']) {
                $passwordMatch = true;
            } elseif (password_verify($currentPassword, $user['password'])) {
                $passwordMatch = true;
            }
            
            if (!$passwordMatch) {
                $_SESSION['error'] = 'Mật khẩu hiện tại không đúng!';
                header('Location: /public/student/profile');
                exit;
            }
            
            // Cập nhật mật khẩu mới (hash)
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $result = $userModel->updatePassword($userId, $hashedPassword);
            
            if ($result) {
                $_SESSION['success'] = 'Đổi mật khẩu thành công!';
            } else {
                $_SESSION['error'] = 'Đổi mật khẩu thất bại!';
            }
            
            header('Location: /public/student/profile');
            exit;
        }
    }
}
