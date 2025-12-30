<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý người dùng</h2>
                <a href="/PHP-CN/public/admin/createUser" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm người dùng
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Thống kê nhanh -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Quản trị viên</h6>
                            <h3><?= $data['total_admins'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Giảng viên</h6>
                            <h3><?= $data['total_teachers'] ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Sinh viên</h6>
                            <h3><?= $data['total_students'] ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bộ lọc và tìm kiếm -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/PHP-CN/public/admin/users">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Vai trò</label>
                                <select class="form-select" name="role" id="filterRole">
                                    <option value="all" <?= $data['current_role'] === 'all' ? 'selected' : '' ?>>Tất cả vai trò</option>
                                    <option value="admin" <?= $data['current_role'] === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                                    <option value="teacher" <?= $data['current_role'] === 'teacher' ? 'selected' : '' ?>>Giảng viên</option>
                                    <option value="student" <?= $data['current_role'] === 'student' ? 'selected' : '' ?>>Sinh viên</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tìm kiếm</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       placeholder="Tìm theo tên, email, mã số..." 
                                       value="<?= htmlspecialchars($data['current_search']) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <?php if ($data['current_role'] !== 'all' || !empty($data['current_search'])): ?>
                        <div class="mt-3">
                            <a href="/PHP-CN/public/admin/users" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Xóa bộ lọc
                            </a>
                            <span class="text-muted ms-2">
                                Tìm thấy <strong><?= count($data['users']) ?></strong> kết quả
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Bảng danh sách -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Vai trò</th>
                                    <th>Mã SV</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['users'] as $user): ?>
                                <tr>
                                    <td><?= $user['user_id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'admin' => 'danger',
                                            'teacher' => 'success',
                                            'student' => 'primary'
                                        ];
                                        $roleNames = [
                                            'admin' => 'Quản trị',
                                            'teacher' => 'Giảng viên',
                                            'student' => 'Sinh viên'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $badges[$user['role']] ?>">
                                            <?= $roleNames[$user['role']] ?>
                                        </span>
                                    </td>
                                    <td><?= $user['student_code'] ?? '-' ?></td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <a href="/PHP-CN/public/admin/editUser/<?= $user['user_id'] ?>" 
                                           class="btn btn-sm btn-warning"
                                           title="Chỉnh sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal<?= $user['user_id'] ?>"
                                                title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Modal xác nhận xóa -->
                                <div class="modal fade" id="deleteModal<?= $user['user_id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Xác nhận xóa</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Bạn có chắc chắn muốn xóa người dùng:</p>
                                                <div class="alert alert-warning">
                                                    <p class="mb-1"><strong>Tên:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                                                    <p class="mb-1"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                                                    <p class="mb-0"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                                                </div>
                                                <p class="text-danger small mb-0">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> 
                                                    Hành động này không thể hoàn tác!
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <a href="/PHP-CN/public/admin/deleteUser/<?= $user['user_id'] ?>" 
                                                   class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Xác nhận xóa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
