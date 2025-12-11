<div class="sidebar bg-success text-white p-0">
    <div class="sidebar-header p-3 border-bottom border-light text-center">
        <img src="<?= BASE_URL ?>/images/logoTVU.png" alt="Logo TVU" style="width: 80px; height: 80px; margin-bottom: 10px; background: white; border-radius: 50%; padding: 5px;">
        <h5 class="mb-1">Giảng viên</h5>
        <p class="mb-0 fw-bold"><?= $_SESSION['full_name'] ?? 'Giảng viên' ?></p>
        <small class="text-light d-block">Mã GV: <?= $_SESSION['username'] ?? '' ?></small>
        <small class="text-light">Khoa CNTT - TVU</small>
    </div>
    <nav class="nav flex-column p-3">
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher/topics">
            <i class="bi bi-journal-text"></i> Quản lý đề tài
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher/registrations">
            <i class="bi bi-clipboard-check"></i> Quản lý đăng ký
            <?php 
            if (isset($data['registrations'])) {
                $pendingCount = count(array_filter($data['registrations'], fn($r) => $r['status'] === 'pending'));
                if ($pendingCount > 0): ?>
                    <span class="badge bg-warning text-dark"><?= $pendingCount ?></span>
                <?php endif;
            }
            ?>
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher/students">
            <i class="bi bi-people-fill"></i> Sinh viên hướng dẫn
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher/sendNotificationForm">
            <i class="bi bi-bell-fill"></i> Gửi thông báo
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/chat">
            <i class="bi bi-chat-dots-fill"></i> Tin nhắn
        </a>
        <a class="nav-link text-white" href="<?= BASE_URL ?>/teacher/profile">
            <i class="bi bi-person-circle"></i> Thông tin cá nhân
        </a>
        <hr class="border-light">
        <a class="nav-link text-danger" href="<?= BASE_URL ?>/logout">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </nav>
</div>
