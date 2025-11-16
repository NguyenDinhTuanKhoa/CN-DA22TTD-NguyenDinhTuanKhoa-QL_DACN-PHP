<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><?= $data['action'] === 'create' ? 'Thêm người dùng mới' : 'Chỉnh sửa người dùng' ?></h2>
                <a href="/PHP-CN/public/admin/users" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person-fill"></i> 
                                <?= $data['action'] === 'create' ? 'Thông tin người dùng mới' : 'Cập nhật thông tin' ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= $data['action'] === 'create' ? '/PHP-CN/public/admin/createUser' : '/PHP-CN/public/admin/editUser/' . $data['user']['user_id'] ?>" onsubmit="console.log('Form submitting...', new FormData(this)); return true;">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="username" 
                                               class="form-control" 
                                               value="<?= $data['user']['username'] ?? '' ?>"
                                               <?= $data['action'] === 'edit' ? 'readonly' : 'required' ?>>
                                        <?php if ($data['action'] === 'edit'): ?>
                                            <small class="text-muted">Không thể thay đổi tên đăng nhập</small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <?= $data['action'] === 'create' ? 'Mật khẩu' : 'Mật khẩu mới' ?> 
                                            <?= $data['action'] === 'create' ? '<span class="text-danger">*</span>' : '' ?>
                                        </label>
                                        <input type="password" 
                                               name="password" 
                                               class="form-control" 
                                               placeholder="<?= $data['action'] === 'edit' ? 'Để trống nếu không đổi' : 'Nhập mật khẩu' ?>"
                                               <?= $data['action'] === 'create' ? 'required' : '' ?>>
                                        <?php if ($data['action'] === 'edit'): ?>
                                            <small class="text-muted">Chỉ nhập nếu muốn đổi mật khẩu</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="full_name" 
                                           class="form-control" 
                                           value="<?= $data['user']['full_name'] ?? '' ?>"
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           value="<?= $data['user']['email'] ?? '' ?>"
                                           required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                                        <select name="role" class="form-select" required>
                                            <option value="">-- Chọn vai trò --</option>
                                            <option value="admin" <?= isset($data['user']) && $data['user']['role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                                            <option value="teacher" <?= isset($data['user']) && $data['user']['role'] === 'teacher' ? 'selected' : '' ?>>Giảng viên</option>
                                            <option value="student" <?= isset($data['user']) && $data['user']['role'] === 'student' ? 'selected' : '' ?>>Sinh viên</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mã số (SV/GV)</label>
                                        <input type="text" 
                                               name="student_code" 
                                               class="form-control" 
                                               value="<?= $data['user']['student_code'] ?? '' ?>"
                                               placeholder="Ví dụ: 110122094 hoặc 00248">
                                        <small class="text-muted">Bắt buộc với Sinh viên và Giảng viên</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" 
                                           name="phone" 
                                           class="form-control" 
                                           value="<?= $data['user']['phone'] ?? '' ?>"
                                           placeholder="0123456789">
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between">
                                    <a href="/PHP-CN/public/admin/users" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> 
                                        <?= $data['action'] === 'create' ? 'Thêm người dùng' : 'Cập nhật' ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Hướng dẫn</h5>
                        </div>
                        <div class="card-body">
                            <h6>Vai trò:</h6>
                            <ul class="small">
                                <li><strong>Admin:</strong> Quản lý toàn bộ hệ thống</li>
                                <li><strong>Giảng viên:</strong> Quản lý đề tài và sinh viên</li>
                                <li><strong>Sinh viên:</strong> Đăng ký và thực hiện đồ án</li>
                            </ul>
                            
                            <hr>
                            
                            <h6>Lưu ý:</h6>
                            <ul class="small mb-0">
                                <li>Tên đăng nhập không thể thay đổi sau khi tạo</li>
                                <li>Mật khẩu sẽ được mã hóa an toàn</li>
                                <li>Email phải là duy nhất trong hệ thống</li>
                                <li>Mã số bắt buộc với Sinh viên và Giảng viên</li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if ($data['action'] === 'edit'): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Thông tin</h5>
                        </div>
                        <div class="card-body">
                            <p class="small mb-2"><strong>Ngày tạo:</strong><br><?= date('d/m/Y H:i', strtotime($data['user']['created_at'])) ?></p>
                            <p class="small mb-0"><strong>Cập nhật lần cuối:</strong><br><?= date('d/m/Y H:i', strtotime($data['user']['updated_at'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
