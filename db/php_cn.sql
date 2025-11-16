-- ============================================
-- HỆ THỐNG QUẢN LÝ ĐỒ ÁN CNTT
-- Trường Đại học Trà Vinh
-- Phiên bản: 1.0.0 (Optimized)
-- Ngày: 09/11/2025
-- ============================================

USE {{DB_NAME}};

-- Set charset UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- Drop tables nếu tồn tại (để import lại)
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS submissions;
DROP TABLE IF EXISTS progress_reports;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS topics;
DROP TABLE IF EXISTS time_settings;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS=1;

-- ============================================
-- BẢNG NGƯỜI DÙNG
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
    student_code VARCHAR(50) UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    
    -- Indexes
    INDEX idx_role (role),
    INDEX idx_student_code (student_code),
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG CẤU HÌNH THỜI GIAN
-- ============================================
CREATE TABLE IF NOT EXISTS time_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(100) NOT NULL,
    setting_type ENUM('topic_creation', 'topic_registration', 'progress_report', 'submission') NOT NULL DEFAULT 'topic_creation' COMMENT 'Loại cài đặt thời gian',
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1 COMMENT '1=Mở, 0=Khóa',
    auto_lock TINYINT(1) DEFAULT 1 COMMENT 'Tự động khóa khi hết hạn',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes
    INDEX idx_setting_type (setting_type),
    INDEX idx_is_active (is_active),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG ĐỀ TÀI
-- ============================================
CREATE TABLE IF NOT EXISTS topics (
    topic_id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    title VARCHAR(500) NOT NULL,
    description TEXT,
    requirements TEXT,
    max_students INT DEFAULT 1 CHECK (max_students > 0 AND max_students <= 10),
    current_students INT DEFAULT 0 CHECK (current_students >= 0),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (teacher_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_teacher_id (teacher_id),
    INDEX idx_status (status),
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG ĐĂNG KÝ ĐỀ TÀI
-- ============================================
CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    topic_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(topic_id) ON DELETE CASCADE,
    
    -- Constraints
    UNIQUE KEY unique_student_topic (student_id, topic_id),
    
    -- Indexes
    INDEX idx_student_id (student_id),
    INDEX idx_topic_id (topic_id),
    INDEX idx_status (status),
    INDEX idx_registered_at (registered_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG BÁO CÁO TIẾN ĐỘ (4 TUẦN)
-- ============================================
CREATE TABLE IF NOT EXISTS progress_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    week_number INT NOT NULL CHECK (week_number BETWEEN 1 AND 4),
    task_name VARCHAR(500) NOT NULL,
    status ENUM('completed', 'incomplete') DEFAULT 'incomplete',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (registration_id) REFERENCES registrations(registration_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_registration_id (registration_id),
    INDEX idx_week_number (week_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG THÔNG BÁO
-- ============================================
CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    
    -- Foreign Keys
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_receiver_id (receiver_id),
    INDEX idx_sender_id (sender_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG NỘP BÀI
-- ============================================
CREATE TABLE IF NOT EXISTS submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    google_drive_link VARCHAR(500),
    github_link VARCHAR(500),
    note TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (registration_id) REFERENCES registrations(registration_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_registration_id (registration_id),
    INDEX idx_submitted_at (submitted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG SESSIONS (Quản lý phiên đăng nhập)
-- ============================================
CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- BẢNG ACTIVITY LOGS (Nhật ký hoạt động)
-- ============================================
CREATE TABLE IF NOT EXISTS activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_value TEXT,
    new_value TEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_table_name (table_name),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert admin mặc định (password: admin123)
INSERT INTO users (username, password, full_name, email, role) 
VALUES ('admin', 'admin123', 'Quản trị viên', 'admin@tvu.edu.vn', 'admin')
ON DUPLICATE KEY UPDATE username=username;

-- Insert dữ liệu giảng viên (username và password là mã giảng viên)
INSERT INTO users (username, password, full_name, email, role, student_code) VALUES
('00248', '00248', 'Phạm Minh Đương', '00248@tvu.edu.vn', 'teacher', '00248'),
('00249', '00249', 'Hà Thị Thúy Vi', '00249@tvu.edu.vn', 'teacher', '00249'),
('00250', '00250', 'Võ Thành C', '00250@tvu.edu.vn', 'teacher', '00250'),
('00251', '00251', 'Trịnh Quốc Việt', '00251@tvu.edu.vn', 'teacher', '00251'),
('00252', '00252', 'Trầm Hoàng Nam', '00252@tvu.edu.vn', 'teacher', '00252'),
('00253', '00253', 'Đoàn Phước Miền', '00253@tvu.edu.vn', 'teacher', '00253'),
('00254', '00254', 'Ngô Thanh Huy', '00254@tvu.edu.vn', 'teacher', '00254'),
('00255', '00255', 'Phạm Thị Trúc Mai', '00255@tvu.edu.vn', 'teacher', '00255'),
('00256', '00256', 'Lê Thị Thùy Lan', '00256@tvu.edu.vn', 'teacher', '00256'),
('00257', '00257', 'Nguyễn Mộng Hiền', '00257@tvu.edu.vn', 'teacher', '00257'),
('00258', '00258', 'Nhan Minh Phúc', '00258@tvu.edu.vn', 'teacher', '00258'),
('00259', '00259', 'Trịnh Thị Anh Duyên', '00259@tvu.edu.vn', 'teacher', '00259'),
('00260', '00260', 'Đặng Hữu Phúc', '00260@tvu.edu.vn', 'teacher', '00260'),
('00261', '00261', 'Trần Song Toàn', '00261@tvu.edu.vn', 'teacher', '00261'),
('00262', '00262', 'Phạm Minh Triết', '00262@tvu.edu.vn', 'teacher', '00262'),
('00264', '00264', 'Nguyễn Thanh Tần', '00264@tvu.edu.vn', 'teacher', '00264'),
('00265', '00265', 'Trần Thị Sen', '00265@tvu.edu.vn', 'teacher', '00265'),
('00267', '00267', 'Phan Văn Tuân', '00267@tvu.edu.vn', 'teacher', '00267'),
('00268', '00268', 'Trương Văn Mến', '00268@tvu.edu.vn', 'teacher', '00268'),
('00269', '00269', 'Dương Minh Hùng', '00269@tvu.edu.vn', 'teacher', '00269'),
('00696', '00696', 'Lê Minh Hải', '00696@tvu.edu.vn', 'teacher', '00696'),
('00707', '00707', 'Nguyễn Phú Nhuận', '00707@tvu.edu.vn', 'teacher', '00707'),
('00823', '00823', 'Thạch Vũ Đình Vi', '00823@tvu.edu.vn', 'teacher', '00823'),
('02405', '02405', 'Nguyễn Thanh Hiền', '02405@tvu.edu.vn', 'teacher', '02405'),
('03539', '03539', 'Lê Minh Tự', '03539@tvu.edu.vn', 'teacher', '03539'),
('03546', '03546', 'Phan Thị Phương Nam', '03546@tvu.edu.vn', 'teacher', '03546'),
('03562', '03562', 'Nguyễn Khắc Quốc', '03562@tvu.edu.vn', 'teacher', '03562'),
('06742', '06742', 'Ngô Thanh Hà', '06742@tvu.edu.vn', 'teacher', '06742'),
('10101', '10101', 'Hồ Ngọc Hà', '10101@tvu.edu.vn', 'teacher', '10101'),
('12661', '12661', 'Võ Phước Hưng', '12661@tvu.edu.vn', 'teacher', '12661'),
('12692', '12692', 'Đặng Hoàng Minh', '12692@tvu.edu.vn', 'teacher', '12692'),
('12694', '12694', 'Lê Thanh Tùng', '12694@tvu.edu.vn', 'teacher', '12694'),
('12695', '12695', 'Nguyễn Ngọc Tiền', '12695@tvu.edu.vn', 'teacher', '12695'),
('12696', '12696', 'Thạch Thị Viasana', '12696@tvu.edu.vn', 'teacher', '12696'),
('12700', '12700', 'Khấu Văn Nhựt', '12700@tvu.edu.vn', 'teacher', '12700'),
('12701', '12701', 'Trần Văn Nam', '12701@tvu.edu.vn', 'teacher', '12701'),
('12702', '12702', 'Nguyễn Thừa Phát Tài', '12702@tvu.edu.vn', 'teacher', '12702'),
('12703', '12703', 'Nguyễn Hoàng Vũ', '12703@tvu.edu.vn', 'teacher', '12703'),
('12704', '12704', 'Kim Anh Tuấn', '12704@tvu.edu.vn', 'teacher', '12704'),
('12705', '12705', 'Nguyễn Trần Diễm Hạnh', '12705@tvu.edu.vn', 'teacher', '12705')
ON DUPLICATE KEY UPDATE username=username;

-- Insert một số sinh viên mẫu
INSERT INTO users (username, password, full_name, email, role, student_code) VALUES
('110122001', '110122001', 'Nguyễn Văn A', '110122001@sv.tvu.edu.vn', 'student', '110122001'),
('110122002', '110122002', 'Trần Thị B', '110122002@sv.tvu.edu.vn', 'student', '110122002'),
('110122003', '110122003', 'Lê Văn C', '110122003@sv.tvu.edu.vn', 'student', '110122003'),
('110122004', '110122004', 'Phạm Thị D', '110122004@sv.tvu.edu.vn', 'student', '110122004'),
('110122005', '110122005', 'Hoàng Văn E', '110122005@sv.tvu.edu.vn', 'student', '110122005')
ON DUPLICATE KEY UPDATE username=username;
-- Insert sinh viên lớp DA22TTD khóa 2022
-- Username và Password đều là mã số sinh viên

INSERT INTO users (username, password, full_name, email, role, student_code) VALUES
('110122249', '110122249', 'Lâm Tinh Tú', '110122249@sv.tvu.edu.vn', 'student', '110122249'),
('110122248', '110122248', 'Nguyễn Thanh Triệu', '110122248@sv.tvu.edu.vn', 'student', '110122248'),
('110122246', '110122246', 'Trần Thanh Thưởng', '110122246@sv.tvu.edu.vn', 'student', '110122246'),
('110122243', '110122243', 'Phạm Duy Tân', '110122243@sv.tvu.edu.vn', 'student', '110122243'),
('110122106', '110122106', 'Mai Hồng Lợi', '110122106@sv.tvu.edu.vn', 'student', '110122106'),
('110122105', '110122105', 'Nguyễn Đỗ Thành Lộc', '110122105@sv.tvu.edu.vn', 'student', '110122105'),
('110122103', '110122103', 'Hà Gia Lộc', '110122103@sv.tvu.edu.vn', 'student', '110122103'),
('110122102', '110122102', 'Nguyễn Hoàng Lăm', '110122102@sv.tvu.edu.vn', 'student', '110122102'),
('110122076', '110122076', 'Phạm Trung Hiếu', '110122076@sv.tvu.edu.vn', 'student', '110122076'),
('110122075', '110122075', 'Đặng Minh Hiếu', '110122075@sv.tvu.edu.vn', 'student', '110122075'),
('110122074', '110122074', 'Đàm Thúy Hiền', '110122074@sv.tvu.edu.vn', 'student', '110122074'),
('110122069', '110122069', 'Nguyễn Thị Ngọc Hân', '110122069@sv.tvu.edu.vn', 'student', '110122069'),
('110122071', '110122071', 'Lâm Nhật Hào', '110122071@sv.tvu.edu.vn', 'student', '110122071'),
('110122070', '110122070', 'Đỗ Gia Hào', '110122070@sv.tvu.edu.vn', 'student', '110122070'),
('110122068', '110122068', 'Võ Chí Hải', '110122068@sv.tvu.edu.vn', 'student', '110122068'),
('110122066', '110122066', 'Trương Hoàng Giang', '110122066@sv.tvu.edu.vn', 'student', '110122066'),
('110122055', '110122055', 'Trần Minh Đức', '110122055@sv.tvu.edu.vn', 'student', '110122055'),
('110122054', '110122054', 'Trần Lâm Phú Đức', '110122054@sv.tvu.edu.vn', 'student', '110122054'),
('110122064', '110122064', 'Trương Mỹ Duyên', '110122064@sv.tvu.edu.vn', 'student', '110122064'),
('110122062', '110122062', 'Nguyễn Thanh Duy', '110122062@sv.tvu.edu.vn', 'student', '110122062'),
('110122061', '110122061', 'Nguyễn Lê Duy', '110122061@sv.tvu.edu.vn', 'student', '110122061'),
('110122060', '110122060', 'Lê Hà Duy', '110122060@sv.tvu.edu.vn', 'student', '110122060'),
('110122059', '110122059', 'Huỳnh Khánh Duy', '110122059@sv.tvu.edu.vn', 'student', '110122059'),
('110122058', '110122058', 'Đào Công Duy', '110122058@sv.tvu.edu.vn', 'student', '110122058'),
('110122056', '110122056', 'Hồ Nguyễn Quốc Dũng', '110122056@sv.tvu.edu.vn', 'student', '110122056'),
('110122028', '110122028', 'Liễu Kiện An', '110122028@sv.tvu.edu.vn', 'student', '110122028'),
('110122101', '110122101', 'Phùng Quốc Kiệt', '110122101@sv.tvu.edu.vn', 'student', '110122101'),
('110122100', '110122100', 'Huỳnh Quốc Kiệt', '110122100@sv.tvu.edu.vn', 'student', '110122100'),
('110122099', '110122099', 'Hoàng Tuấn Kiệt', '110122099@sv.tvu.edu.vn', 'student', '110122099'),
('110122098', '110122098', 'Đặng Gia Kiệt', '110122098@sv.tvu.edu.vn', 'student', '110122098'),
('110122097', '110122097', 'Nguyễn Minh Khởi', '110122097@sv.tvu.edu.vn', 'student', '110122097'),
('110122095', '110122095', 'Nguyễn Ngọc Anh Khoa', '110122095@sv.tvu.edu.vn', 'student', '110122095'),
('110122094', '110122094', 'Nguyễn Đinh Tuấn Khoa', '110122094@sv.tvu.edu.vn', 'student', '110122094'),
('110122093', '110122093', 'Hồ Anh Khoa', '110122093@sv.tvu.edu.vn', 'student', '110122093'),
('110122092', '110122092', 'Ngô Huỳnh Quốc Khang', '110122092@sv.tvu.edu.vn', 'student', '110122092'),
('110122090', '110122090', 'La Thuấn Khang', '110122090@sv.tvu.edu.vn', 'student', '110122090'),
('110122089', '110122089', 'Phan Đình Khải', '110122089@sv.tvu.edu.vn', 'student', '110122089'),
('110122087', '110122087', 'Trầm Tấn Khá', '110122087@sv.tvu.edu.vn', 'student', '110122087'),
('110122086', '110122086', 'Lê Tuấn Kha', '110122086@sv.tvu.edu.vn', 'student', '110122086'),
('110122083', '110122083', 'Đỗ Thị Kim Hương', '110122083@sv.tvu.edu.vn', 'student', '110122083'),
('110122082', '110122082', 'Châu Thị Mỹ Hương', '110122082@sv.tvu.edu.vn', 'student', '110122082'),
('110122081', '110122081', 'Trần Tấn Hưng', '110122081@sv.tvu.edu.vn', 'student', '110122081'),
('110122079', '110122079', 'Nguyễn Phi Hùng', '110122079@sv.tvu.edu.vn', 'student', '110122079'),
('110122078', '110122078', 'Nguyễn Văn Hoàng', '110122078@sv.tvu.edu.vn', 'student', '110122078'),
('110122077', '110122077', 'Huỳnh Minh Khải Hoàn', '110122077@sv.tvu.edu.vn', 'student', '110122077')
ON DUPLICATE KEY UPDATE 
    full_name = VALUES(full_name),
    email = VALUES(email);

-- Thông báo
SELECT '✅ Đã thêm 45 sinh viên lớp DA22TTD vào database' as message;
SELECT 'Username và Password đều là mã số sinh viên' as note;
SELECT 'Ví dụ: Username: 110122094, Password: 110122094' as example;

-- Insert đề tài mẫu
INSERT INTO topics (teacher_id, title, description, requirements, max_students, status) VALUES
-- Đề tài của GV Phạm Minh Đương (00248)
((SELECT user_id FROM users WHERE username = '00248'), 
 'Xây dựng ứng dụng quản lý thư viện trực tuyến', 
 'Phát triển hệ thống quản lý thư viện với các chức năng: quản lý sách, mượn/trả sách, tìm kiếm, thống kê. Hệ thống hỗ trợ cả web và mobile.',
 'Kiến thức: PHP/Laravel hoặc Node.js, MySQL, HTML/CSS/JavaScript. Kỹ năng: Lập trình web, thiết kế database, làm việc nhóm.',
 2, 'approved'),

((SELECT user_id FROM users WHERE username = '00248'), 
 'Ứng dụng học tập trực tuyến với AI',
 'Xây dựng nền tảng học tập tích hợp AI để gợi ý khóa học, đánh giá tiến độ học tập và tạo bài tập tự động.',
 'Kiến thức: Python, Machine Learning cơ bản, Web framework (Flask/Django). Kỹ năng: Xử lý dữ liệu, API integration.',
 1, 'approved'),

-- Đề tài của GV Hà Thị Thúy Vi (00249)
((SELECT user_id FROM users WHERE username = '00249'), 
 'Hệ thống quản lý bán hàng và kho',
 'Phát triển phần mềm quản lý bán hàng, nhập xuất kho, báo cáo doanh thu cho cửa hàng nhỏ.',
 'Kiến thức: Java/C#, SQL Server/MySQL, Desktop application. Kỹ năng: Phân tích nghiệp vụ, thiết kế giao diện.',
 2, 'approved'),

((SELECT user_id FROM users WHERE username = '00249'), 
 'Website thương mại điện tử với thanh toán online',
 'Xây dựng website bán hàng tích hợp thanh toán VNPay, Momo, quản lý đơn hàng, khách hàng.',
 'Kiến thức: PHP/Laravel, MySQL, Payment Gateway API. Kỹ năng: Bảo mật web, xử lý giao dịch.',
 1, 'approved'),

-- Đề tài của GV Võ Thành C (00250)
((SELECT user_id FROM users WHERE username = '00250'), 
 'Ứng dụng di động quản lý chi tiêu cá nhân',
 'Phát triển app mobile giúp người dùng theo dõi thu chi, lập kế hoạch tài chính, thống kê chi tiêu.',
 'Kiến thức: React Native hoặc Flutter, Firebase/SQLite. Kỹ năng: Mobile development, UI/UX design.',
 2, 'approved'),

-- Đề tài của GV Trịnh Quốc Việt (00251)
((SELECT user_id FROM users WHERE username = '00251'), 
 'Hệ thống IoT giám sát môi trường',
 'Xây dựng hệ thống giám sát nhiệt độ, độ ẩm, chất lượng không khí sử dụng Arduino/ESP32 và web dashboard.',
 'Kiến thức: Arduino/ESP32, MQTT, Web (Node.js/Python). Kỹ năng: IoT, xử lý sensor, real-time data.',
 1, 'approved'),

-- Đề tài của GV Trầm Hoàng Nam (00252)
((SELECT user_id FROM users WHERE username = '00252'), 
 'Chatbot hỗ trợ tư vấn tuyển sinh',
 'Phát triển chatbot AI để tư vấn thông tin tuyển sinh, ngành học, học phí cho sinh viên.',
 'Kiến thức: Python, NLP, Dialogflow/Rasa, Web framework. Kỹ năng: Machine Learning, xử lý ngôn ngữ tự nhiên.',
 1, 'approved'),

-- Đề tài của GV Đoàn Phước Miền (00253)
((SELECT user_id FROM users WHERE username = '00253'), 
 'Game giáo dục cho trẻ em',
 'Phát triển game học toán, tiếng Anh cho trẻ tiểu học với đồ họa sinh động và âm thanh hấp dẫn.',
 'Kiến thức: Unity/Godot, C#/GDScript, Game design. Kỹ năng: Lập trình game, thiết kế đồ họa.',
 2, 'approved'),

-- Đề tài của GV Ngô Thanh Huy (00254)
((SELECT user_id FROM users WHERE username = '00254'), 
 'Hệ thống nhận diện khuôn mặt điểm danh',
 'Xây dựng hệ thống điểm danh tự động bằng nhận diện khuôn mặt sử dụng Deep Learning.',
 'Kiến thức: Python, OpenCV, TensorFlow/PyTorch, Face Recognition. Kỹ năng: Computer Vision, Deep Learning.',
 1, 'approved'),

-- Đề tài của GV Phạm Thị Trúc Mai (00255)
((SELECT user_id FROM users WHERE username = '00255'), 
 'Website tin tức với CMS tùy chỉnh',
 'Phát triển website tin tức có hệ thống quản lý nội dung, phân quyền biên tập viên, SEO optimization.',
 'Kiến thức: PHP/Laravel hoặc WordPress, MySQL, SEO. Kỹ năng: CMS development, content management.',
 2, 'approved');

-- ============================================
-- DỮ LIỆU CÀI ĐẶT THỜI GIAN
-- ============================================
INSERT INTO time_settings (setting_name, setting_type, start_date, end_date, description, is_active, auto_lock) VALUES
('Thời gian ra đề tài', 'topic_creation', '2024-01-01 00:00:00', '2024-01-31 23:59:59', 'Giảng viên có thể tạo và chỉnh sửa đề tài trong khoảng thời gian này', 1, 1),
('Thời gian đăng ký đề tài', 'topic_registration', '2024-01-15 00:00:00', '2024-02-15 23:59:59', 'Sinh viên có thể đăng ký đề tài trong khoảng thời gian này', 1, 1),
('Thời gian báo cáo tiến độ', 'progress_report', '2024-01-22 00:00:00', '2024-03-15 23:59:59', 'Sinh viên cập nhật báo cáo tiến độ 4 tuần', 1, 1),
('Thời gian nộp bài', 'submission', '2024-03-01 00:00:00', '2024-03-31 23:59:59', 'Sinh viên nộp bài đồ án cuối kỳ', 1, 1)
ON DUPLICATE KEY UPDATE setting_name=setting_name;

-- ============================================
-- HOÀN TẤT
-- ============================================
-- Bật lại foreign key checks
SET FOREIGN_KEY_CHECKS=1;

-- Thông báo
SELECT '✅ Database đã được tạo thành công!' as status;
SELECT 'Tổng số bảng: 10' as info;
SELECT 'Charset: UTF8MB4' as encoding;
SELECT 'Engine: InnoDB' as engine;
SELECT 'Indexes: Đã tối ưu' as performance;
SELECT 'Constraints: Đầy đủ' as validation;

-- ============================================
-- THỐNG KÊ DỮ LIỆU
-- ============================================
-- 1 Admin
-- 40 Giảng viên  
-- 50 Sinh viên (5 mẫu + 45 DA22TTD)
-- 10 Đề tài mẫu
-- 4 Cài đặt thời gian
-- ============================================
