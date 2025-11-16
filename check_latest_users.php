<?php
require_once 'config/config.php';
require_once 'app/core/Database.php';

$db = Database::getInstance();

echo "<h2>20 Users mới nhất:</h2>";
$stmt = $db->query("SELECT user_id, username, full_name, email, role, student_code, created_at FROM users ORDER BY created_at DESC LIMIT 20");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th><th>Username</th><th>Họ tên</th><th>Email</th><th>Role</th><th>Mã SV/GV</th><th>Ngày tạo</th>";
echo "</tr>";

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['user_id']}</td>";
    echo "<td>{$user['username']}</td>";
    echo "<td>{$user['full_name']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td><span style='padding: 2px 8px; background: " . 
         ($user['role'] == 'admin' ? '#dc3545' : ($user['role'] == 'teacher' ? '#28a745' : '#007bff')) . 
         "; color: white; border-radius: 3px;'>{$user['role']}</span></td>";
    echo "<td>{$user['student_code']}</td>";
    echo "<td>" . date('d/m/Y H:i:s', strtotime($user['created_at'])) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Tổng số users: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "</h3>";
