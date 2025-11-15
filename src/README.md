# CN-DA22TTD-NguyenDinhTuanKhoa-QL_DACN-PHP

Hệ thống Quản lý Đồ án Chuyên ngành - PHP

## Mô tả dự án
Hệ thống quản lý đồ án chuyên ngành được xây dựng bằng PHP, MySQL và Bootstrap. Hệ thống hỗ trợ quản lý đề tài, đăng ký đề tài, theo dõi tiến độ và nộp bài đồ án.

## Tính năng chính

### Dành cho Sinh viên
- Xem danh sách đề tài
- Đăng ký đề tài
- Báo cáo tiến độ
- Nộp bài đồ án
- Nhận thông báo

### Dành cho Giảng viên
- Quản lý đề tài
- Duyệt đăng ký sinh viên
- Theo dõi tiến độ sinh viên
- Chấm điểm đồ án
- Gửi thông báo

### Dành cho Admin
- Quản lý người dùng
- Quản lý đề tài
- Quản lý đăng ký
- Thống kê báo cáo

## Cấu trúc thư mục
```
├── app/
│   ├── controllers/     # Controllers xử lý logic
│   ├── models/          # Models tương tác database
│   └── views/           # Views hiển thị giao diện
├── config/              # File cấu hình
├── db/                  # Database scripts
├── public/              # Tài nguyên public (CSS, JS, images)
└── .htaccess           # URL rewriting
```

## Yêu cầu hệ thống
- PHP >= 7.4
- MySQL >= 5.7
- Apache/Nginx với mod_rewrite
- Composer (optional)

## Cài đặt

1. Clone repository:
```bash
git clone https://github.com/NguyenDinhTuanKhoa/CN-DA22TTD-NguyenDinhTuanKhoa-QL_DACN-PHP.git
```

2. Import database từ file `db/database.sql`

3. Cấu hình database trong `config/database.php`

4. Truy cập: `http://localhost/PHP-CN/public/`

## Công nghệ sử dụng
- **Backend:** PHP (MVC Pattern)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5
- **Icons:** Bootstrap Icons

## Tác giả
Nguyễn Đình Tuấn Khoa - DA22TTD

## License
MIT License
