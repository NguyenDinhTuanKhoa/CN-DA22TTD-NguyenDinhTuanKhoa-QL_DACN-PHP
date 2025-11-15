<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Thông tin cá nhân</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-fill"></i> Cập nhật thông tin</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/PHP-CN/public/student/updateProfile">
                                <div class="mb-3">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control" value="<?= $data['user']['username'] ?>" disabled>
                                    <small class="text-muted">Không thể thay đổi tên đăng nhập</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mã sinh viên</label>
                                    <input type="text" class="form-control" value="<?= $data['user']['student_code'] ?>" disabled>
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
                                
                                <input type="hidden" name="role" value="<?= $data['user']['role'] ?>">
                                <input type="hidden" name="student_code" value="<?= $data['user']['student_code'] ?>">
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Thông tin tài khoản</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Vai trò:</strong> <span class="badge bg-primary">Sinh viên</span></p>
                            <p><strong>Ngày tạo:</strong> <?= date('d/m/Y', strtotime($data['user']['created_at'])) ?></p>
                            <hr>
                            <h6>Đổi mật khẩu</h6>
                            <p class="text-muted small">Liên hệ quản trị viên để đổi mật khẩu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
