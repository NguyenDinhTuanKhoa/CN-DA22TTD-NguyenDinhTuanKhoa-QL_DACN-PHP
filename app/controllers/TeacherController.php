<?php
class TeacherController extends Controller {
    
    private function checkTeacherSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug session
        if (!isset($_SESSION['user_id'])) {
            echo "❌ Session không có user_id<br>";
            echo "Session data: ";
            print_r($_SESSION);
            die();
        }
        
        if ($_SESSION['role'] !== 'teacher') {
            echo "❌ Role không phải teacher: " . $_SESSION['role'] . "<br>";
            die();
        }
        
        return $_SESSION['user_id'];
    }
    
    public function index() {
        try {
            $teacherId = $this->checkTeacherSession();
            
            $topicModel = $this->model('Topic');
            $registrationModel = $this->model('Registration');
            
            $topics = $topicModel->getByTeacherId($teacherId);
            $registrations = $registrationModel->getByTeacherId($teacherId);
            
            $data = [
                'title' => 'Trang giảng viên',
                'teacher_id' => $teacherId,
                'topics' => $topics,
                'registrations' => $registrations,
                'total_topics' => count($topics),
                'total_students' => count($registrations)
            ];
            
            $this->view('teacher/dashboard', $data);
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            echo "<br>File: " . $e->getFile();
            echo "<br>Line: " . $e->getLine();
            die();
        }
    }
    
    public function topics() {
        $teacherId = $this->checkTeacherSession();
        
        $topicModel = $this->model('Topic');
        $timeModel = $this->model('TimeSetting');
        
        $data = [
            'title' => 'Quản lý đề tài',
            'topics' => $topicModel->getByTeacherId($teacherId),
            'can_create' => $timeModel->isFeatureEnabled('topic_creation'),
            'creation_time' => $timeModel->getByType('topic_creation')
        ];
        
        $this->view('teacher/topics', $data);
    }
    
    public function createTopic() {
        $teacherId = $this->checkTeacherSession();
        
        // Kiểm tra thời gian được phép tạo đề tài
        $timeModel = $this->model('TimeSetting');
        if (!$timeModel->isFeatureEnabled('topic_creation')) {
            $_SESSION['error'] = 'Chức năng tạo đề tài hiện đang bị khóa hoặc chưa đến thời gian!';
            header('Location: /PHP-CN/public/teacher/topics');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topicModel = $this->model('Topic');
            $_POST['teacher_id'] = $teacherId;
            $result = $topicModel->create($_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Tạo đề tài thành công! Đề tài đang chờ admin duyệt.';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi tạo đề tài!';
            }
            
            header('Location: /PHP-CN/public/teacher/topics');
            exit;
        }
        
        $this->view('teacher/topic_form', ['title' => 'Tạo đề tài mới', 'action' => 'create']);
    }
    
    public function editTopic($topicId) {
        $teacherId = $this->checkTeacherSession();
        
        $topicModel = $this->model('Topic');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $topicModel->update($topicId, $_POST);
            
            if ($result) {
                $_SESSION['success'] = 'Cập nhật đề tài thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật đề tài!';
            }
            
            header('Location: /PHP-CN/public/teacher/topics');
            exit;
        }
        
        $data = [
            'title' => 'Chỉnh sửa đề tài',
            'topic' => $topicModel->getById($topicId),
            'action' => 'edit'
        ];
        
        $this->view('teacher/topic_form', $data);
    }
    
    public function deleteTopic($topicId) {
        $this->checkTeacherSession();
        
        $topicModel = $this->model('Topic');
        $result = $topicModel->delete($topicId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa đề tài thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa đề tài!';
        }
        
        header('Location: /PHP-CN/public/teacher/topics');
        exit;
    }
    
    public function students() {
        $teacherId = $this->checkTeacherSession();
        
        $registrationModel = $this->model('Registration');
        $data = [
            'title' => 'Sinh viên hướng dẫn',
            'registrations' => $registrationModel->getByTeacherId($teacherId)
        ];
        
        $this->view('teacher/students', $data);
    }
    
    public function sendNotification() {
        $teacherId = $this->checkTeacherSession();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationModel = $this->model('Notification');
            $registrationModel = $this->model('Registration');
            
            $recipientType = $_POST['recipient_type'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            
            if ($recipientType === 'all') {
                // Gửi đến tất cả sinh viên của giáo viên
                $students = $registrationModel->getByTeacherId($teacherId);
                $count = 0;
                
                foreach ($students as $student) {
                    if ($student['status'] === 'approved') {
                        $notificationModel->create($teacherId, $student['student_id'], $title, $content);
                        $count++;
                    }
                }
                
                $_SESSION['success'] = "Đã gửi thông báo đến $count sinh viên!";
            } else {
                // Gửi đến sinh viên cụ thể
                $studentId = $_POST['student_id'];
                $notificationModel->create($teacherId, $studentId, $title, $content);
                $_SESSION['success'] = 'Đã gửi thông báo thành công!';
            }
            
            header('Location: /PHP-CN/public/teacher/sendNotificationForm');
            exit;
        }
        
        // Hiển thị form
        $registrationModel = $this->model('Registration');
        $students = $registrationModel->getByTeacherId($teacherId);
        
        // Chỉ lấy sinh viên đã được duyệt
        $approvedStudents = array_filter($students, function($s) {
            return $s['status'] === 'approved';
        });
        
        $data = [
            'title' => 'Gửi thông báo',
            'students' => $approvedStudents
        ];
        
        $this->view('teacher/send_notification', $data);
    }
    
    public function sendNotificationForm() {
        $this->sendNotification();
    }
    
    public function progress($registrationId) {
        $this->checkTeacherSession();
        
        $registrationModel = $this->model('Registration');
        $progressModel = $this->model('ProgressReport');
        $submissionModel = $this->model('Submission');
        
        $data = [
            'title' => 'Theo dõi tiến độ',
            'registration' => $registrationModel->getByRegistrationId($registrationId),
            'reports' => $progressModel->getByRegistrationId($registrationId),
            'submission' => $submissionModel->getByRegistrationId($registrationId)
        ];
        
        $this->view('teacher/progress', $data);
    }
    
    public function registrations() {
        $teacherId = $this->checkTeacherSession();
        
        $registrationModel = $this->model('Registration');
        $data = [
            'title' => 'Quản lý đăng ký',
            'registrations' => $registrationModel->getByTeacherId($teacherId)
        ];
        
        $this->view('teacher/registrations', $data);
    }
    
    public function approveRegistration($registrationId) {
        $teacherId = $this->checkTeacherSession();
        
        $registrationModel = $this->model('Registration');
        $notificationModel = $this->model('Notification');
        
        // Cập nhật trạng thái
        $result = $registrationModel->updateStatus($registrationId, 'approved');
        
        if ($result) {
            // Lấy thông tin đăng ký
            $registration = $registrationModel->getByRegistrationId($registrationId);
            
            // Gửi thông báo cho sinh viên
            $notificationModel->create(
                $teacherId,
                $registration['student_id'],
                'Đăng ký đề tài được chấp nhận',
                "Đề tài '{$registration['topic_title']}' của bạn đã được giảng viên chấp nhận. Hãy bắt đầu thực hiện đồ án và cập nhật tiến độ định kỳ."
            );
            
            $_SESSION['success'] = 'Đã chấp nhận sinh viên ' . $registration['student_name'];
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi chấp nhận đăng ký';
        }
        
        header('Location: /PHP-CN/public/teacher/registrations');
        exit;
    }
    
    public function rejectRegistration($registrationId) {
        $teacherId = $this->checkTeacherSession();
        
        $registrationModel = $this->model('Registration');
        $notificationModel = $this->model('Notification');
        $topicModel = $this->model('Topic');
        
        // Lấy thông tin đăng ký trước khi từ chối
        $registration = $registrationModel->getByRegistrationId($registrationId);
        
        // Giảm số lượng sinh viên đã đăng ký
        $topicModel->decreaseStudentCount($registration['topic_id']);
        
        // Gửi thông báo cho sinh viên
        $notificationModel->create(
            $teacherId,
            $registration['student_id'],
            'Đăng ký đề tài bị từ chối',
            "Rất tiếc, đề tài '{$registration['topic_title']}' của bạn đã bị từ chối. Bạn có thể đăng ký đề tài khác hoặc liên hệ giảng viên để biết thêm chi tiết."
        );
        
        // Xóa đăng ký (cho phép sinh viên đăng ký lại)
        $result = $registrationModel->deleteRegistration($registrationId);
        
        if ($result) {
            $_SESSION['success'] = 'Đã từ chối sinh viên ' . $registration['student_name'];
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi từ chối đăng ký';
        }
        
        header('Location: /PHP-CN/public/teacher/registrations');
        exit;
    }
}