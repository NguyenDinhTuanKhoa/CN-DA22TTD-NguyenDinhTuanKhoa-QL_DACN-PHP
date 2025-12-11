<div class="sidebar bg-dark text-white p-0">
    <div class="sidebar-header p-3 border-bottom border-secondary text-center">
        <img src="<?= BASE_URL ?>/images/logoTVU.png" alt="Logo TVU" style="width: 80px; height: 80px; margin-bottom: 10px; background: white; border-radius: 50%; padding: 5px;">
        <h5 class="mb-1">Quản trị viên</h5>
        <p class="mb-0 fw-bold"><?= $_SESSION['full_name'] ?? 'Admin' ?></p>
        <small class="text-muted d-block">Mã: <?= $_SESSION['username'] ?? '' ?></small>
        <small class="text-muted">Khoa CNTT - TVU</small>
    </div>
    <nav class="nav flex-column p-3">
        <a class="nav-link text-white" href="<?= BASE_URL ?>/admin">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/users">
            <i class="bi bi-people-fill"></i> Quản lý người dùng
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/topics">
            <i class="bi bi-journal-text"></i> Quản lý đề tài
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/admin/submissions">
            <i class="bi bi-file-earmark-check"></i> Tổng hợp bài nộp
        </a>
        
        <!-- Phần Cài đặt -->
        <div class="mt-3 mb-2 px-2">
            <span class="text-secondary small text-uppercase fw-bold">
                <i class="bi bi-gear-fill"></i> CÀI ĐẶT
            </span>
        </div>
        <a class="nav-link text-dark rounded mb-2" href="<?= BASE_URL ?>/admin/semesters" 
           style="background: linear-gradient(135deg, #f5a623 0%, #f7931e 100%);">
            <i class="bi bi-calendar-range"></i> Năm học/Học kỳ
        </a>
        <a class="nav-link text-dark rounded" href="<?= BASE_URL ?>/admin/timeSettings"
           style="background: linear-gradient(135deg, #ffd700 0%, #ffb800 100%);">
            <i class="bi bi-clock-history"></i> Thời gian đăng ký
        </a>
        
        <hr class="border-secondary">
        <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </nav>
</div>
