<?php
session_start();
require_once 'config/config.php';
require_once 'app/core/Database.php';
require_once 'app/models/User.php';

echo "<h2>🔍 DEBUG THÊM USER</h2>";

// Simulate POST data như từ form
$_POST = [
    'username' => 'testuser123',
    'password' => '123456',
    'full_name' => 'Nguyễn Test User',
    'email' => 'testuser123@example.com',
    'role' => 'student',
    'student_code' => 'TEST123',
    'phone' => '0987654321'
];

echo "<h3>📝 Dữ liệu POST:</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Test User model
$userModel = new User();

echo "<h3>🔧 Test User Model Create:</h3>";
$result = $userModel->create($_POST);

echo "<p><strong>Kết quả create():</strong> " . ($result ? '✅ TRUE' : '❌ FALSE') . "</p>";

// Kiểm tra session error
if (isset($_SESSION['error'])) {
    echo "<p style='color: red;'><strong>Session Error:</strong> " . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}

// Kiểm tra xem user có được tạo không
$db = Database::getInstance();
$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_POST['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h3>🔍 Kiểm tra trong database:</h3>";
if ($user) {
    echo "<p style='color: green;'>✅ User đã được tạo trong database!</p>";
    echo "<pre>";
    print_r($user);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ User KHÔNG có trong database!</p>";
}

// Test trực tiếp với PDO
echo "<h3>🧪 Test insert trực tiếp:</h3>";
try {
    $stmt = $db->prepare("INSERT INTO users (username, password, full_name, email, role, student_code, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $directResult = $stmt->execute([
        'direct_test_' . time(),
        password_hash('123456', PASSWORD_DEFAULT),
        'Direct Test User',
        'direct_' . time() . '@test.com',
        'student',
        'DIRECT' . time(),
        '0123456789'
    ]);
    
    echo "<p><strong>Insert trực tiếp:</strong> " . ($directResult ? '✅ THÀNH CÔNG' : '❌ THẤT BẠI') . "</p>";
    
    if ($directResult) {
        echo "<p>Last Insert ID: " . $db->lastInsertId() . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Lỗi insert trực tiếp: " . $e->getMessage() . "</p>";
}