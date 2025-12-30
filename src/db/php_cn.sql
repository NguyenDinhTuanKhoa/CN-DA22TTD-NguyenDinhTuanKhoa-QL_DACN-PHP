-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 06, 2025 lúc 12:05 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `php_cn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_groups`
--

CREATE TABLE `chat_groups` (
  `group_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_groups`
--

INSERT INTO `chat_groups` (`group_id`, `teacher_id`, `group_name`, `created_at`) VALUES
(1, 7, 'Nhóm hướng dẫn - Đoàn Phước Miền', '2025-12-06 10:55:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_group_members`
--

CREATE TABLE `chat_group_members` (
  `member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_group_members`
--

INSERT INTO `chat_group_members` (`member_id`, `group_id`, `user_id`, `joined_at`) VALUES
(1, 1, 7, '2025-12-06 10:55:51'),
(2, 1, 79, '2025-12-06 10:55:51'),
(3, 1, 80, '2025-12-06 10:55:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `message_type` enum('text','file','image') DEFAULT 'text',
  `file_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chat_messages`
--

INSERT INTO `chat_messages` (`message_id`, `group_id`, `sender_id`, `message_text`, `message_type`, `file_url`, `created_at`) VALUES
(1, 1, 7, 'hello', 'text', NULL, '2025-12-06 10:56:35'),
(2, 1, 7, 'hello', 'text', NULL, '2025-12-06 10:56:56'),
(3, 1, 7, 'hello', 'text', NULL, '2025-12-06 10:59:07'),
(4, 1, 7, 'hello', 'text', NULL, '2025-12-06 11:00:55'),
(5, 1, 7, 'hello', 'text', NULL, '2025-12-06 11:00:59'),
(6, 1, 7, 'hello', 'text', NULL, '2025-12-06 11:01:05'),
(7, 1, 7, 'helllo', 'text', NULL, '2025-12-06 11:01:33'),
(8, 1, 7, '/', 'text', NULL, '2025-12-06 11:01:43'),
(9, 1, 7, '1', 'text', NULL, '2025-12-06 11:03:05'),
(10, 1, 7, '1', 'text', NULL, '2025-12-06 11:03:11'),
(11, 1, 7, 'hello', 'text', NULL, '2025-12-06 11:03:34'),
(12, 1, 7, 'hello', 'text', NULL, '2025-12-06 11:03:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`notification_id`, `sender_id`, `receiver_id`, `title`, `content`, `is_read`, `created_at`, `read_at`) VALUES
(1, 7, 79, 'Đăng ký đề tài bị từ chối', 'Rất tiếc, đề tài \'Game giáo dục cho trẻ em\' của bạn đã bị từ chối. Bạn có thể đăng ký đề tài khác hoặc liên hệ giảng viên để biết thêm chi tiết.', 0, '2025-11-12 07:44:39', NULL),
(2, 4, 79, 'Đăng ký đề tài bị từ chối', 'Rất tiếc, đề tài \'Ứng dụng di động quản lý chi tiêu cá nhân\' của bạn đã bị từ chối. Bạn có thể đăng ký đề tài khác hoặc liên hệ giảng viên để biết thêm chi tiết.', 0, '2025-11-12 12:03:57', NULL),
(3, 7, 79, 'Đăng ký đề tài được chấp nhận', 'Đề tài \'Game giáo dục cho trẻ em\' của bạn đã được giảng viên chấp nhận. Hãy bắt đầu thực hiện đồ án và cập nhật tiến độ định kỳ.', 0, '2025-11-12 12:05:43', NULL),
(4, 7, 79, 'thứ 5 họp mặt tại c7 nhé ', 'thầy sẽ sửa code cho mấy em \r\n', 0, '2025-11-12 12:08:55', NULL),
(5, 7, 79, 'khoa làm xong chưa', 'khoa làm xong chưa\r\n', 0, '2025-11-30 07:47:26', NULL),
(6, 7, 80, 'Đăng ký đề tài được chấp nhận', 'Đề tài \' Website review sách và cộng đồng đọc sách trực tuyến\' của bạn đã được giảng viên chấp nhận. Hãy bắt đầu thực hiện đồ án và cập nhật tiến độ định kỳ.', 0, '2025-12-06 10:48:56', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `progress_reports`
--

CREATE TABLE `progress_reports` (
  `report_id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `week_number` int(11) NOT NULL CHECK (`week_number` between 1 and 4),
  `task_name` varchar(500) NOT NULL,
  `status` enum('completed','incomplete') DEFAULT 'incomplete',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `registrations`
--

INSERT INTO `registrations` (`registration_id`, `student_id`, `topic_id`, `status`, `registered_at`, `approved_at`) VALUES
(3, 79, 8, 'approved', '2025-11-12 12:04:49', NULL),
(4, 81, 1, 'pending', '2025-11-13 12:38:25', NULL),
(5, 80, 12, 'approved', '2025-12-05 16:08:00', '2025-12-06 10:48:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `semester_number` tinyint(4) NOT NULL DEFAULT 1,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `registration_deadline` date DEFAULT NULL,
  `submission_deadline` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `submissions`
--

CREATE TABLE `submissions` (
  `submission_id` int(11) NOT NULL,
  `registration_id` int(11) NOT NULL,
  `google_drive_link` varchar(500) DEFAULT NULL,
  `github_link` varchar(500) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `time_settings`
--

CREATE TABLE `time_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_type` enum('topic_creation','topic_registration','progress_report','submission') NOT NULL DEFAULT 'topic_creation' COMMENT 'Loại cài đặt thời gian',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1=Mở, 0=Khóa',
  `auto_lock` tinyint(1) DEFAULT 1 COMMENT 'Tự động khóa khi hết hạn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `time_settings`
--

INSERT INTO `time_settings` (`setting_id`, `setting_name`, `setting_type`, `start_date`, `end_date`, `description`, `is_active`, `auto_lock`, `created_at`, `updated_at`) VALUES
(1, 'Thời gian ra đề tài', 'topic_creation', '2025-11-01 00:00:00', '2026-03-16 23:59:00', 'Giảng viên có thể tạo và chỉnh sửa đề tài trong khoảng thời gian này', 1, 1, '2025-11-10 14:19:01', '2025-11-16 03:25:00'),
(2, 'Thời gian đăng ký đề tài', 'topic_registration', '2024-01-15 00:00:00', '2026-11-15 23:59:00', 'Sinh viên có thể đăng ký đề tài trong khoảng thời gian này', 1, 1, '2025-11-10 14:19:01', '2025-12-05 15:18:01'),
(3, 'Thời gian báo cáo tiến độ', 'progress_report', '2024-01-22 00:00:00', '2026-05-15 23:59:00', 'Sinh viên cập nhật báo cáo tiến độ 4 tuần', 1, 1, '2025-11-10 14:19:01', '2025-12-05 15:18:35'),
(4, 'Thời gian nộp bài', 'submission', '2024-03-01 00:00:00', '2026-12-31 23:59:00', 'Sinh viên nộp bài đồ án cuối kỳ', 1, 1, '2025-11-10 14:19:01', '2025-12-05 15:18:57'),
(5, 'giáo viên ra đề tài ', 'topic_creation', '2025-11-14 21:35:00', '2026-02-15 21:35:00', '', 1, 1, '2025-11-15 14:36:26', '2025-11-15 14:46:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `max_students` int(11) DEFAULT 1 CHECK (`max_students` > 0 and `max_students` <= 10),
  `current_students` int(11) DEFAULT 0 CHECK (`current_students` >= 0),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `topics`
--

INSERT INTO `topics` (`topic_id`, `teacher_id`, `title`, `description`, `requirements`, `max_students`, `current_students`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'Xây dựng ứng dụng quản lý thư viện trực tuyến', 'Phát triển hệ thống quản lý thư viện với các chức năng: quản lý sách, mượn/trả sách, tìm kiếm, thống kê. Hệ thống hỗ trợ cả web và mobile.', 'Kiến thức: PHP/Laravel hoặc Node.js, MySQL, HTML/CSS/JavaScript. Kỹ năng: Lập trình web, thiết kế database, làm việc nhóm.', 2, 1, 'approved', '2025-11-10 14:19:01', '2025-11-13 12:38:25', NULL),
(2, 2, 'Ứng dụng học tập trực tuyến với AI', 'Xây dựng nền tảng học tập tích hợp AI để gợi ý khóa học, đánh giá tiến độ học tập và tạo bài tập tự động.', 'Kiến thức: Python, Machine Learning cơ bản, Web framework (Flask/Django). Kỹ năng: Xử lý dữ liệu, API integration.', 1, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(3, 3, 'Hệ thống quản lý bán hàng và kho', 'Phát triển phần mềm quản lý bán hàng, nhập xuất kho, báo cáo doanh thu cho cửa hàng nhỏ.', 'Kiến thức: Java/C#, SQL Server/MySQL, Desktop application. Kỹ năng: Phân tích nghiệp vụ, thiết kế giao diện.', 2, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(4, 3, 'Website thương mại điện tử với thanh toán online', 'Xây dựng website bán hàng tích hợp thanh toán VNPay, Momo, quản lý đơn hàng, khách hàng.', 'Kiến thức: PHP/Laravel, MySQL, Payment Gateway API. Kỹ năng: Bảo mật web, xử lý giao dịch.', 1, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(5, 4, 'Ứng dụng di động quản lý chi tiêu cá nhân', 'Phát triển app mobile giúp người dùng theo dõi thu chi, lập kế hoạch tài chính, thống kê chi tiêu.', 'Kiến thức: React Native hoặc Flutter, Firebase/SQLite. Kỹ năng: Mobile development, UI/UX design.', 2, 0, 'approved', '2025-11-10 14:19:01', '2025-11-12 12:03:57', NULL),
(6, 5, 'Hệ thống IoT giám sát môi trường', 'Xây dựng hệ thống giám sát nhiệt độ, độ ẩm, chất lượng không khí sử dụng Arduino/ESP32 và web dashboard.', 'Kiến thức: Arduino/ESP32, MQTT, Web (Node.js/Python). Kỹ năng: IoT, xử lý sensor, real-time data.', 1, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(7, 6, 'Chatbot hỗ trợ tư vấn tuyển sinh', 'Phát triển chatbot AI để tư vấn thông tin tuyển sinh, ngành học, học phí cho sinh viên.', 'Kiến thức: Python, NLP, Dialogflow/Rasa, Web framework. Kỹ năng: Machine Learning, xử lý ngôn ngữ tự nhiên.', 1, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(8, 7, 'Game giáo dục cho trẻ em', 'Phát triển game học toán, tiếng Anh cho trẻ tiểu học với đồ họa sinh động và âm thanh hấp dẫn.', 'Kiến thức: Unity/Godot, C#/GDScript, Game design. Kỹ năng: Lập trình game, thiết kế đồ họa.', 1, 1, 'approved', '2025-11-10 14:19:01', '2025-11-12 12:04:49', NULL),
(9, 8, 'Hệ thống nhận diện khuôn mặt điểm danh', 'Xây dựng hệ thống điểm danh tự động bằng nhận diện khuôn mặt sử dụng Deep Learning.', 'Kiến thức: Python, OpenCV, TensorFlow/PyTorch, Face Recognition. Kỹ năng: Computer Vision, Deep Learning.', 1, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(10, 9, 'Website tin tức với CMS tùy chỉnh', 'Phát triển website tin tức có hệ thống quản lý nội dung, phân quyền biên tập viên, SEO optimization.', 'Kiến thức: PHP/Laravel hoặc WordPress, MySQL, SEO. Kỹ năng: CMS development, content management.', 2, 0, 'approved', '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL),
(12, 7, ' Website review sách và cộng đồng đọc sách trực tuyến', ' Mô tả: Xây dựng một nền tảng trực tuyến cho phép người dùng chia sẻ đánh giá, bình luận về các cuốn sách đã đọc, tạo ra một cộng đồng đọc sách sôi động. Website có các chức năng như tạo nhóm đọc sách, thảo luận về sách, đề xuất sách hay.', ' Công nghệ: Frontend (ReactJS, Angular, VueJS), Backend (NodeJS, Python/Django), Database (MongoDB)\r\n• Tính năng chính:\r\n* Đăng tải review sách, đánh giá sách\r\n* Bình luận về review của người khác\r\n* Tạo nhóm đọc sách theo chủ đề\r\n* Thảo luận về sách trong nhóm\r\n* Đề xuất sách dựa trên sở thích và đánh giá của người dùng\r\n* Quản lý tài khoản người dùng\r\n• Độ khó: Trung bình', 1, 1, 'approved', '2025-12-05 12:30:07', '2025-12-05 16:08:00', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `student_code` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `student_code`, `phone`, `created_at`, `updated_at`, `deleted_at`, `last_login`) VALUES
(1, 'admin', 'admin123', 'Quản trị viên', 'admin@tvu.edu.vn', 'admin', NULL, NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(2, '00248', '00248', 'Phạm Minh Đương', '00248@tvu.edu.vn', 'teacher', '00248', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(3, '00249', '00249', 'Hà Thị Thúy Vi', '00249@tvu.edu.vn', 'teacher', '00249', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(4, '00250', '00250', 'Võ Thành C', '00250@tvu.edu.vn', 'teacher', '00250', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(5, '00251', '00251', 'Trịnh Quốc Việt', '00251@tvu.edu.vn', 'teacher', '00251', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(6, '00252', '00252', 'Trầm Hoàng Nam', '00252@tvu.edu.vn', 'teacher', '00252', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(7, '00253', '00253', 'Đoàn Phước Miền', '00253@tvu.edu.vn', 'teacher', '00253', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(8, '00254', '00254', 'Ngô Thanh Huy', '00254@tvu.edu.vn', 'teacher', '00254', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(9, '00255', '00255', 'Phạm Thị Trúc Mai', '00255@tvu.edu.vn', 'teacher', '00255', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(10, '00256', '00256', 'Lê Thị Thùy Lan', '00256@tvu.edu.vn', 'teacher', '00256', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(11, '00257', '00257', 'Nguyễn Mộng Hiền', '00257@tvu.edu.vn', 'teacher', '00257', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(12, '00258', '00258', 'Nhan Minh Phúc', '00258@tvu.edu.vn', 'teacher', '00258', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(13, '00259', '00259', 'Trịnh Thị Anh Duyên', '00259@tvu.edu.vn', 'teacher', '00259', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(14, '00260', '00260', 'Đặng Hữu Phúc', '00260@tvu.edu.vn', 'teacher', '00260', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(15, '00261', '00261', 'Trần Song Toàn', '00261@tvu.edu.vn', 'teacher', '00261', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(16, '00262', '00262', 'Phạm Minh Triết', '00262@tvu.edu.vn', 'teacher', '00262', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(17, '00264', '00264', 'Nguyễn Thanh Tần', '00264@tvu.edu.vn', 'teacher', '00264', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(18, '00265', '00265', 'Trần Thị Sen', '00265@tvu.edu.vn', 'teacher', '00265', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(19, '00267', '00267', 'Phan Văn Tuân', '00267@tvu.edu.vn', 'teacher', '00267', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(20, '00268', '00268', 'Trương Văn Mến', '00268@tvu.edu.vn', 'teacher', '00268', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(21, '00269', '00269', 'Dương Minh Hùng', '00269@tvu.edu.vn', 'teacher', '00269', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(22, '00696', '00696', 'Lê Minh Hải', '00696@tvu.edu.vn', 'teacher', '00696', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(23, '00707', '00707', 'Nguyễn Phú Nhuận', '00707@tvu.edu.vn', 'teacher', '00707', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(24, '00823', '00823', 'Thạch Vũ Đình Vi', '00823@tvu.edu.vn', 'teacher', '00823', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(25, '02405', '02405', 'Nguyễn Thanh Hiền', '02405@tvu.edu.vn', 'teacher', '02405', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(26, '03539', '03539', 'Lê Minh Tự', '03539@tvu.edu.vn', 'teacher', '03539', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(27, '03546', '03546', 'Phan Thị Phương Nam', '03546@tvu.edu.vn', 'teacher', '03546', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(28, '03562', '03562', 'Nguyễn Khắc Quốc', '03562@tvu.edu.vn', 'teacher', '03562', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(29, '06742', '06742', 'Ngô Thanh Hà', '06742@tvu.edu.vn', 'teacher', '06742', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(30, '10101', '10101', 'Hồ Ngọc Hà', '10101@tvu.edu.vn', 'teacher', '10101', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(31, '12661', '12661', 'Võ Phước Hưng', '12661@tvu.edu.vn', 'teacher', '12661', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(32, '12692', '12692', 'Đặng Hoàng Minh', '12692@tvu.edu.vn', 'teacher', '12692', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(33, '12694', '12694', 'Lê Thanh Tùng', '12694@tvu.edu.vn', 'teacher', '12694', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(34, '12695', '12695', 'Nguyễn Ngọc Tiền', '12695@tvu.edu.vn', 'teacher', '12695', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(35, '12696', '12696', 'Thạch Thị Viasana', '12696@tvu.edu.vn', 'teacher', '12696', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(36, '12700', '12700', 'Khấu Văn Nhựt', '12700@tvu.edu.vn', 'teacher', '12700', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(37, '12701', '12701', 'Trần Văn Nam', '12701@tvu.edu.vn', 'teacher', '12701', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(38, '12702', '12702', 'Nguyễn Thừa Phát Tài', '12702@tvu.edu.vn', 'teacher', '12702', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(39, '12703', '12703', 'Nguyễn Hoàng Vũ', '12703@tvu.edu.vn', 'teacher', '12703', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(40, '12704', '12704', 'Kim Anh Tuấn', '12704@tvu.edu.vn', 'teacher', '12704', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(41, '12705', '12705', 'Nguyễn Trần Diễm Hạnh', '12705@tvu.edu.vn', 'teacher', '12705', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(42, '110122001', '110122001', 'Nguyễn Văn A', '110122001@sv.tvu.edu.vn', 'student', '110122001', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(43, '110122002', '110122002', 'Trần Thị B', '110122002@sv.tvu.edu.vn', 'student', '110122002', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(44, '110122003', '110122003', 'Lê Văn C', '110122003@sv.tvu.edu.vn', 'student', '110122003', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(45, '110122004', '110122004', 'Phạm Thị D', '110122004@sv.tvu.edu.vn', 'student', '110122004', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(46, '110122005', '110122005', 'Hoàng Văn E', '110122005@sv.tvu.edu.vn', 'student', '110122005', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(47, '110122249', '110122249', 'Lâm Tinh Tú', '110122249@sv.tvu.edu.vn', 'student', '110122249', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(48, '110122248', '110122248', 'Nguyễn Thanh Triệu', '110122248@sv.tvu.edu.vn', 'student', '110122248', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(49, '110122246', '110122246', 'Trần Thanh Thưởng', '110122246@sv.tvu.edu.vn', 'student', '110122246', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(50, '110122243', '110122243', 'Phạm Duy Tân', '110122243@sv.tvu.edu.vn', 'student', '110122243', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(51, '110122106', '110122106', 'Mai Hồng Lợi', '110122106@sv.tvu.edu.vn', 'student', '110122106', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(52, '110122105', '110122105', 'Nguyễn Đỗ Thành Lộc', '110122105@sv.tvu.edu.vn', 'student', '110122105', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(53, '110122103', '110122103', 'Hà Gia Lộc', '110122103@sv.tvu.edu.vn', 'student', '110122103', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(54, '110122102', '110122102', 'Nguyễn Hoàng Lăm', '110122102@sv.tvu.edu.vn', 'student', '110122102', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(55, '110122076', '110122076', 'Phạm Trung Hiếu', '110122076@sv.tvu.edu.vn', 'student', '110122076', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(56, '110122075', '110122075', 'Đặng Minh Hiếu', '110122075@sv.tvu.edu.vn', 'student', '110122075', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(57, '110122074', '110122074', 'Đàm Thúy Hiền', '110122074@sv.tvu.edu.vn', 'student', '110122074', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(58, '110122069', '110122069', 'Nguyễn Thị Ngọc Hân', '110122069@sv.tvu.edu.vn', 'student', '110122069', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(59, '110122071', '110122071', 'Lâm Nhật Hào', '110122071@sv.tvu.edu.vn', 'student', '110122071', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(60, '110122070', '110122070', 'Đỗ Gia Hào', '110122070@sv.tvu.edu.vn', 'student', '110122070', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(61, '110122068', '110122068', 'Võ Chí Hải', '110122068@sv.tvu.edu.vn', 'student', '110122068', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(62, '110122066', '110122066', 'Trương Hoàng Giang', '110122066@sv.tvu.edu.vn', 'student', '110122066', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(63, '110122055', '110122055', 'Trần Minh Đức', '110122055@sv.tvu.edu.vn', 'student', '110122055', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(64, '110122054', '110122054', 'Trần Lâm Phú Đức', '110122054@sv.tvu.edu.vn', 'student', '110122054', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(65, '110122064', '110122064', 'Trương Mỹ Duyên', '110122064@sv.tvu.edu.vn', 'student', '110122064', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(66, '110122062', '110122062', 'Nguyễn Thanh Duy', '110122062@sv.tvu.edu.vn', 'student', '110122062', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(67, '110122061', '110122061', 'Nguyễn Lê Duy', '110122061@sv.tvu.edu.vn', 'student', '110122061', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(68, '110122060', '110122060', 'Lê Hà Duy', '110122060@sv.tvu.edu.vn', 'student', '110122060', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(69, '110122059', '110122059', 'Huỳnh Khánh Duy', '110122059@sv.tvu.edu.vn', 'student', '110122059', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(70, '110122058', '110122058', 'Đào Công Duy', '110122058@sv.tvu.edu.vn', 'student', '110122058', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(71, '110122056', '110122056', 'Hồ Nguyễn Quốc Dũng', '110122056@sv.tvu.edu.vn', 'student', '110122056', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(72, '110122028', '110122028', 'Liễu Kiện An', '110122028@sv.tvu.edu.vn', 'student', '110122028', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(73, '110122101', '110122101', 'Phùng Quốc Kiệt', '110122101@sv.tvu.edu.vn', 'student', '110122101', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(74, '110122100', '110122100', 'Huỳnh Quốc Kiệt', '110122100@sv.tvu.edu.vn', 'student', '110122100', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(75, '110122099', '110122099', 'Hoàng Tuấn Kiệt', '110122099@sv.tvu.edu.vn', 'student', '110122099', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(76, '110122098', '110122098', 'Đặng Gia Kiệt', '110122098@sv.tvu.edu.vn', 'student', '110122098', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(77, '110122097', '110122097', 'Nguyễn Minh Khởi', '110122097@sv.tvu.edu.vn', 'student', '110122097', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(78, '110122095', '110122095', 'Nguyễn Ngọc Anh Khoa', '110122095@sv.tvu.edu.vn', 'student', '110122095', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(79, '110122094', '110122094', 'Nguyễn Đinh Tuấn Khoa', '110122094@sv.tvu.edu.vn', 'student', '110122094', '', '2025-11-10 14:19:01', '2025-11-12 09:51:54', NULL, NULL),
(80, '110122093', '110122093', 'Hồ Anh Khoa', '110122093@sv.tvu.edu.vn', 'student', '110122093', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(81, '110122092', '110122092', 'Ngô Huỳnh Quốc Khang', '110122092@sv.tvu.edu.vn', 'student', '110122092', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(82, '110122090', '110122090', 'La Thuấn Khang', '110122090@sv.tvu.edu.vn', 'student', '110122090', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(83, '110122089', '110122089', 'Phan Đình Khải', '110122089@sv.tvu.edu.vn', 'student', '110122089', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(84, '110122087', '110122087', 'Trầm Tấn Khá', '110122087@sv.tvu.edu.vn', 'student', '110122087', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(85, '110122086', '110122086', 'Lê Tuấn Kha', '110122086@sv.tvu.edu.vn', 'student', '110122086', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(86, '110122083', '110122083', 'Đỗ Thị Kim Hương', '110122083@sv.tvu.edu.vn', 'student', '110122083', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(87, '110122082', '110122082', 'Châu Thị Mỹ Hương', '110122082@sv.tvu.edu.vn', 'student', '110122082', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(88, '110122081', '110122081', 'Trần Tấn Hưng', '110122081@sv.tvu.edu.vn', 'student', '110122081', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(89, '110122079', '110122079', 'Nguyễn Phi Hùng', '110122079@sv.tvu.edu.vn', 'student', '110122079', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(90, '110122078', '110122078', 'Nguyễn Văn Hoàng', '110122078@sv.tvu.edu.vn', 'student', '110122078', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(91, '110122077', '110122077', 'Huỳnh Minh Khải Hoàn', '110122077@sv.tvu.edu.vn', 'student', '110122077', NULL, '2025-11-10 14:19:01', '2025-11-10 14:19:01', NULL, NULL),
(94, '112233', '$2y$10$WNUx7nMu4LG9xvV3kUvJDumkqecSHhz9iMNPMrlpgsFkqfShMxxfa', 'bồ câu', 'dinhlonghott496@gmail.com', 'teacher', '112233', '0369312522', '2025-11-12 04:49:09', '2025-11-12 04:49:09', NULL, NULL),
(95, '00001', '$2y$10$vqHM1NKF/azzpFKr9g8xsuBgyE.7bsdEQntL6eeN4rGIOs6566xcC', 'Võ Phước Hưng', 'hung@gmail.com', 'admin', '00001', '', '2025-11-30 11:28:34', '2025-11-30 11:28:34', NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `chat_groups`
--
ALTER TABLE `chat_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`);

--
-- Chỉ mục cho bảng `chat_group_members`
--
ALTER TABLE `chat_group_members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `unique_group_user` (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_receiver_id` (`receiver_id`),
  ADD KEY `idx_sender_id` (`sender_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `progress_reports`
--
ALTER TABLE `progress_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `idx_registration_id` (`registration_id`),
  ADD KEY `idx_week_number` (`week_number`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `unique_student_topic` (`student_id`,`topic_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_topic_id` (`topic_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_registered_at` (`registered_at`);

--
-- Chỉ mục cho bảng `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_last_activity` (`last_activity`);

--
-- Chỉ mục cho bảng `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `idx_registration_id` (`registration_id`),
  ADD KEY `idx_submitted_at` (`submitted_at`);

--
-- Chỉ mục cho bảng `time_settings`
--
ALTER TABLE `time_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_dates` (`start_date`,`end_date`),
  ADD KEY `idx_setting_type` (`setting_type`);

--
-- Chỉ mục cho bảng `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD KEY `idx_teacher_id` (`teacher_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_deleted_at` (`deleted_at`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_code` (`student_code`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_student_code` (`student_code`),
  ADD KEY `idx_deleted_at` (`deleted_at`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chat_groups`
--
ALTER TABLE `chat_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `chat_group_members`
--
ALTER TABLE `chat_group_members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `progress_reports`
--
ALTER TABLE `progress_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `submissions`
--
ALTER TABLE `submissions`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `time_settings`
--
ALTER TABLE `time_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `topics`
--
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_groups`
--
ALTER TABLE `chat_groups`
  ADD CONSTRAINT `chat_groups_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_group_members`
--
ALTER TABLE `chat_group_members`
  ADD CONSTRAINT `chat_group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `chat_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `chat_groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `progress_reports`
--
ALTER TABLE `progress_reports`
  ADD CONSTRAINT `progress_reports_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`registration_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`registration_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
