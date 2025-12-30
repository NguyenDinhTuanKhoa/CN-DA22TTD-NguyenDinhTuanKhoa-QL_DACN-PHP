<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Thông tin cá nhân</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Cập nhật thông tin</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/public/teacher/updateProfile">
                                <div class="mb-3">
                                    <label class="form-label">Mã giảng viên</label>
                                    <input type="text" class="form-control" value="<?= $data['user']['username'] ?>" disabled>
                                    <small class="text-muted">Không thể thay đổi mã giảng viên</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control" value="<?= $data['user']['full_name'] ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="<?= $data['user']['email'] ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= $data['user']['phone'] ?? '' ?>" placeholder="0123456789">
                                </div>
                                
                                <input type="hidden" name="role" value="teacher">
                                <input type="hidden" name="student_code" value="<?= $data['user']['student_code'] ?? $data['user']['username'] ?>">
                                
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Thông tin tài khoản</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Vai trò:</strong> <span class="badge bg-success">Giảng viên</span></p>
                            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y', strtotime($data['user']['created_at'])) ?></p>
                            <p><strong>Số đề tài:</strong> <span class="badge bg-primary"><?= $data['topic_count'] ?? 0 ?></span></p>
                            <p><strong>Sinh viên hướng dẫn:</strong> <span class="badge bg-info"><?= $data['student_count'] ?? 0 ?></span></p>
                        </div>
                    </div>
                    
                    <!-- Form đổi mật khẩu -->
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-key-fill"></i> Đổi mật khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/public/teacher/changePassword" id="changePasswordForm">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6">
                                    <small class="text-muted">Tối thiểu 6 ký tự</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                    <div class="invalid-feedback">Mật khẩu xác nhận không khớp</div>
                                </div>
                                
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="bi bi-check-circle"></i> Đổi mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const newPass = document.getElementById('new_password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    
    if (newPass !== confirmPass) {
        e.preventDefault();
        document.getElementById('confirm_password').classList.add('is-invalid');
        return false;
    }
    document.getElementById('confirm_password').classList.remove('is-invalid');
});

document.getElementById('confirm_password').addEventListener('input', function() {
    const newPass = document.getElementById('new_password').value;
    if (this.value !== newPass) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
