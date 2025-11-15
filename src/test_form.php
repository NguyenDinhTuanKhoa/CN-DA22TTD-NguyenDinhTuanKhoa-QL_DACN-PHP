<?php
session_start();

// Giả lập session admin
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';
$_SESSION['username'] = 'admin';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>🎯 FORM SUBMITTED!</h2>";
    echo "<h3>POST Data:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Test tạo user
    try {
        require_once 'config/config.php';
        require_once 'app/core/Database.php';
        require_once 'app/models/User.php';
        
        echo "<h3>🔧 Test Database Connection:</h3>";
        $db = Database::getInstance();
        echo "✅ Database connected<br>";
        
        echo "<h3>🔧 Test User Model:</h3>";
        $userModel = new User();
        echo "✅ User model created<br>";
        
        echo "<h3>🔧 Test Create User:</h3>";
        $result = $userModel->create($_POST);
        
        echo "<h3>Kết quả tạo user:</h3>";
        echo $result ? "✅ THÀNH CÔNG" : "❌ THẤT BẠI";
        
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red;'>Lỗi: " . $_SESSION['error'] . "</p>";
        }
        
        // Kiểm tra user vừa tạo
        if ($result) {
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo "<h3>✅ User đã được tạo trong database:</h3>";
                echo "<pre>";
                print_r($user);
                echo "</pre>";
            } else {
                echo "<h3>❌ User KHÔNG có trong database!</h3>";
            }
        }
        
    } catch (Exception $e) {
        echo "<h3 style='color: red;'>❌ LỖI:</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>🧪 Test Form Thêm User</h2>
        
        <form method="POST" action="" onsubmit="console.log('Form submitting...'); return true;">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" value="testuser<?= time() ?>" required>
            </div>
            
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" value="123456" required>
            </div>
            
            <div class="mb-3">
                <label>Họ tên:</label>
                <input type="text" name="full_name" class="form-control" value="Test User" required>
            </div>
            
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="test<?= time() ?>@test.com" required>
            </div>
            
            <div class="mb-3">
                <label>Role:</label>
                <select name="role" class="form-control" required>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label>Student Code:</label>
                <input type="text" name="student_code" class="form-control" value="TEST<?= time() ?>">
            </div>
            
            <div class="mb-3">
                <label>Phone:</label>
                <input type="text" name="phone" class="form-control" value="0123456789">
            </div>
            
            <button type="submit" class="btn btn-primary">Thêm User</button>
        </form>
    </div>
</body>
</html>