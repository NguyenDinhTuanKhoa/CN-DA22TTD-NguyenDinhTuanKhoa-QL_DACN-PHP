<div class="sidebar bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
    <div class="text-center mb-4">
        <img src="/PHP-CN/public/images/logoTVU.png" alt="Logo TVU" style="width: 80px; height: 80px; margin-bottom: 10px; background: white; border-radius: 50%; padding: 5px;">
        <h5 class="mt-2"><?= $_SESSION['full_name'] ?? 'Sinh viên' ?></h5>
        <small class="text-muted"><?= $_SESSION['username'] ?? '' ?></small>
    </div>
    
    <nav class="nav flex-column">
        <a href="/PHP-CN/public/student" class="nav-link text-white">
            <i class="bi bi-house-door-fill"></i> Trang chủ
        </a>
        <a href="/PHP-CN/public/student/topics" class="nav-link text-white">
            <i class="bi bi-journal-text"></i> Danh sách đề tài
        </a>
        <a href="/PHP-CN/public/student/progress" class="nav-link text-white">
            <i class="bi bi-graph-up"></i> Báo cáo tiến độ
        </a>
        <a href="/PHP-CN/public/student/notifications" class="nav-link text-white">
            <i class="bi bi-bell-fill"></i> Thông báo
        </a>
        <a href="/PHP-CN/public/student/submission" class="nav-link text-white">
            <i class="bi bi-cloud-upload-fill"></i> Nộp bài đồ án
        </a>
        <a href="/PHP-CN/public/student/profile" class="nav-link text-white">
            <i class="bi bi-person-fill"></i> Thông tin cá nhân
        </a>
        <hr class="bg-secondary">
        <a href="/PHP-CN/public/logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </nav>
</div>
