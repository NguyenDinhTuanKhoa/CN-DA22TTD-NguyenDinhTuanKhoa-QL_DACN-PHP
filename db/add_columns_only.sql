-- Thêm các cột mới vào bảng time_settings
-- Chạy file này trong phpMyAdmin hoặc MySQL command line

USE php_cn;

-- Thêm cột setting_type nếu chưa có
ALTER TABLE time_settings 
ADD COLUMN IF NOT EXISTS setting_type ENUM('topic_creation', 'topic_registration', 'progress_report', 'submission') 
NOT NULL DEFAULT 'topic_creation' COMMENT 'Loại cài đặt thời gian' AFTER setting_name;

-- Thêm cột auto_lock nếu chưa có
ALTER TABLE time_settings 
ADD COLUMN IF NOT EXISTS auto_lock TINYINT(1) DEFAULT 1 COMMENT 'Tự động khóa khi hết hạn' AFTER is_active;

-- Thêm cột updated_at nếu chưa có
ALTER TABLE time_settings 
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

-- Sửa kiểu dữ liệu cột is_active
ALTER TABLE time_settings 
MODIFY COLUMN is_active TINYINT(1) DEFAULT 1 COMMENT '1=Mở, 0=Khóa';

-- Thêm index
ALTER TABLE time_settings 
ADD INDEX IF NOT EXISTS idx_setting_type (setting_type);

-- Cập nhật dữ liệu cũ với setting_type
UPDATE time_settings SET setting_type = 'topic_creation' WHERE setting_name LIKE '%ra đề tài%';
UPDATE time_settings SET setting_type = 'topic_registration' WHERE setting_name LIKE '%đăng ký%';
UPDATE time_settings SET setting_type = 'progress_report' WHERE setting_name LIKE '%báo cáo%';
UPDATE time_settings SET setting_type = 'submission' WHERE setting_name LIKE '%nộp bài%';

-- Cập nhật auto_lock cho tất cả
UPDATE time_settings SET auto_lock = 1 WHERE auto_lock IS NULL;

SELECT 'Cập nhật thành công!' as status;
