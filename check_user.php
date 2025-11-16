<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();

// Kiểm tra user vừa thêm
$username = '110122000'; // Thay bằng username bạn vừa thêm

$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>Kiểm tra user: $username</h2>";

if ($user) {
    echo "<pre>";
    print_r($user);
    echo "</pre>";
} else {
    echo "<p style='color: red;'>❌ Không tìm thấy user này trong database!</p>";
    
    // Kiểm tra 10 user mới nhất
    echo "<h3>10 users mới nhất:</h3>";
    $stmt = $db->query("SELECT user_id, username, full_name, role, created_at FROM users ORDER BY created_at DESC LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Họ tên</th><th>Role</th><th>Ngày tạo</th></tr>";
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>{$u['user_id']}</td>";
        echo "<td>{$u['username']}</td>";
        echo "<td>{$u['full_name']}</td>";
        echo "<td>{$u['role']}</td>";
        echo "<td>{$u['created_at']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
