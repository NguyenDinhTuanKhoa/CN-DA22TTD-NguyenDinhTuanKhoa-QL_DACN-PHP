<?php
/**
 * Script import database tự động
 * Chạy: php import_database.php
 */

// Load config
require_once 'config/config.php';

echo "=== IMPORT DATABASE ===\n";
echo "Database: " . DB_NAME . "\n";
echo "Host: " . DB_HOST . "\n";
echo "User: " . DB_USER . "\n\n";

// Đọc file SQL
$sqlFile = 'db/php_cn.sql';
if (!file_exists($sqlFile)) {
    die("❌ Không tìm thấy file: $sqlFile\n");
}

echo "📂 Đọc file SQL...\n";
$sql = file_get_contents($sqlFile);

// Thay thế {{DB_NAME}} bằng tên database thực tế
$sql = str_replace('{{DB_NAME}}', DB_NAME, $sql);

// Kết nối database
try {
    echo "🔌 Kết nối database...\n";
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
        ]
    );
    
    // Tạo database nếu chưa tồn tại
    echo "📦 Tạo database nếu chưa có...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Tách và thực thi từng câu lệnh SQL
    echo "⚙️  Thực thi SQL...\n";
    
    // Xóa comments
    $sql = preg_replace('/^--.*$/m', '', $sql);
    $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
    
    // Tách theo dấu ;
    $statements = explode(';', $sql);
    
    $count = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement . ';');
                $count++;
                // Hiển thị tiến trình
                if ($count % 10 == 0) {
                    echo "  Đã thực thi $count câu lệnh...\n";
                }
            } catch (PDOException $e) {
                // Bỏ qua lỗi SELECT (thông báo) và một số lỗi không quan trọng
                if (stripos($statement, 'SELECT') !== 0 && 
                    strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "⚠️  Lỗi: " . substr($statement, 0, 50) . "... - " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n✅ Import thành công!\n";
    echo "📊 Đã thực thi $count câu lệnh SQL\n\n";
    
    // Tạo kết nối mới để tránh lỗi unbuffered query
    $pdo2 = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $stmt = $pdo2->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "👥 Tổng số users: " . $result['total'] . "\n";
    
    // Hiển thị thông tin đăng nhập
    echo "\n=== THÔNG TIN ĐĂNG NHẬP ===\n";
    echo "Admin:\n";
    echo "  Username: admin\n";
    echo "  Password: admin123\n\n";
    
    echo "Giảng viên (ví dụ):\n";
    echo "  Username: 00248\n";
    echo "  Password: 00248\n\n";
    
    echo "Sinh viên (ví dụ):\n";
    echo "  Username: 110122094\n";
    echo "  Password: 110122094\n\n";
    
    echo "✨ Hoàn tất! Bạn có thể đăng nhập vào hệ thống.\n";
    
} catch (PDOException $e) {
    die("❌ Lỗi kết nối database: " . $e->getMessage() . "\n");
}
