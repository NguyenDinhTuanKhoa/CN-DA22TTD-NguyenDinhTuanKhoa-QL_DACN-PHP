<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();

// Test insert trực tiếp
$testData = [
    'username' => 'test_' . time(),
    'password' => password_hash('123456', PASSWORD_DEFAULT),
    'full_name' => 'Nguyễn Test',
    'email' => 'test_' . time() . '@test.com',
    'role' => 'student',
    'student_code' => 'TEST' . time(),
    'phone' => '0123456789'
];

echo "<h2>Test Insert User</h2>";
echo "<h3>Dữ liệu test:</h3>";
echo "<pre>";
print_r($testData);
echo "</pre>";

try {
    $stmt = $db->prepare("INSERT INTO users (username, password, full_name, email, role, student_code, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        $testData['username'],
        $testData['password'],
        $testData['full_name'],
        $testData['email'],
        $testData['role'],
        $testData['student_code'],
        $testData['phone']
    ]);
    
    if ($result) {
        $lastId = $db->lastInsertId();
        echo "<p style='color: green;'>✅ Insert thành công! User ID: $lastId</p>";
        
        // Kiểm tra lại
        $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$lastId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<h3>Dữ liệu đã lưu:</h3>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ Insert thất bại!</p>";
        echo "<pre>";
        print_r($stmt->errorInfo());
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Lỗi: " . $e->getMessage() . "</p>";
}
