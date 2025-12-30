<?php
class Schema {
    public static function run() {
        try {
            // Tạo database nếu chưa có
            $root = Database::getRootConnection();
            $root->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

            // Kết nối database
            $pdo = Database::getInstance();

            // Kiểm tra xem bảng users đã tồn tại chưa
            $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
            $tableExists = $stmt->rowCount() > 0;
            
            // Chỉ import SQL nếu bảng chưa tồn tại (database mới)
            if (!$tableExists) {
                // Đọc và thực thi file SQL
                $sqlFilePath = '../db/php_cn.sql';
                $sql = file_get_contents($sqlFilePath);
                
                if ($sql === false) {
                    throw new Exception("Không thể đọc file SQL");
                }
                
                $sql = str_replace('{{DB_NAME}}', DB_NAME, $sql);
                $pdo->exec($sql);
                
                error_log("Schema: Database initialized successfully");
            }
            
        } catch (Exception $e) {
            error_log("Schema Error: " . $e->getMessage());
        }
    }
}

