<?php
class AuthController extends Controller {
    
    public function login() {
        // Start session trước tiên
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            try {
                // Tìm user theo username
                $stmt = Database::getInstance()->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$username]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Debug: Hiển thị thông tin để kiểm tra
                if (!$user) {
                    $data = [
                        'title' => 'Đăng nhập',
                        'error' => 'Không tìm thấy user: ' . $username
                    ];
                    $this->view('auth/login', $data);
                    return;
                }
                
                // Kiểm tra password (cả plain text và hash)
                $passwordMatch = false;
                if ($password === $user['password']) {
                    $passwordMatch = true; // Plain text match
                } elseif (password_verify($password, $user['password'])) {
                    $passwordMatch = true; // Hash match
                }
                
                if ($passwordMatch) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['role'] = $user['role'];
                    
                    // Xác định URL redirect
                    $redirectUrl = '/PHP-CN/public/';
                    switch ($user['role']) {
                        case 'admin':
                            $redirectUrl = '/PHP-CN/public/admin';
                            break;
                        case 'teacher':
                            $redirectUrl = '/PHP-CN/public/teacher';
                            break;
                        case 'student':
                            $redirectUrl = '/PHP-CN/public/student';
                            break;
                    }
                    
                    // Clear output buffer trước khi redirect
                    if (ob_get_level()) {
                        ob_end_clean();
                    }
                    
                    // Redirect với exit ngay lập tức
                    header('Location: ' . $redirectUrl, true, 302);
                    exit();
                } else {
                    $data = [
                        'title' => 'Đăng nhập',
                        'error' => 'Mật khẩu không đúng cho user: ' . $username
                    ];
                    $this->view('auth/login', $data);
                }
                
            } catch (Exception $e) {
                $data = [
                    'title' => 'Đăng nhập',
                    'error' => 'Lỗi database: ' . $e->getMessage()
                ];
                $this->view('auth/login', $data);
            }
        } else {
            $this->view('auth/login', ['title' => 'Đăng nhập']);
        }
    }
    
    public function logout() {
        session_start();
        session_destroy();
        header('Location: /PHP-CN/public/login');
        exit;
    }
}