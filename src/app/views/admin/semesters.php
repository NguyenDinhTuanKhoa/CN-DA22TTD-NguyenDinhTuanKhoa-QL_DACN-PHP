<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar3"></i> Quản lý Năm học & Học kỳ</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSemesterModal">
                    <i class="bi bi-plus-circle"></i> Thêm học kỳ
                </button>
            </div>
            
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
            
            <!-- Học kỳ đang hoạt động -->
            <?php if ($data['active_semester']): $sem = $data['active_semester']; ?>
            <div class="card mb-4 border-success border-2">
                <div class="card-body" style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <span class="text-success">● Học kỳ đang hoạt động</span>
                            <h4><?= $sem['name'] ?></h4>
                            <small>Năm học <?= $sem['academic_year'] ?></small>
                        </div>
                        <div class="col-md-4">
                            <strong>Bắt đầu:</strong> <?= date('d/m/Y', strtotime($sem['start_date'])) ?><br>
                            <strong>Kết thúc:</strong> <?= date('d/m/Y', strtotime($sem['end_date'])) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Chưa có học kỳ nào được kích hoạt!
            </div>
            <?php endif; ?>

            <!-- Danh sách học kỳ -->
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Danh sách học kỳ</h5></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tên học kỳ</th>
                                <th>Năm học</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($data['semesters'])): ?>
                                <?php foreach ($data['semesters'] as $sem): ?>
                                <tr class="<?= $sem['is_active'] ? 'table-success' : '' ?>">
                                    <td><strong><?= $sem['name'] ?></strong></td>
                                    <td><?= $sem['academic_year'] ?> - HK<?= $sem['semester_number'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($sem['start_date'])) ?> — <?= date('d/m/Y', strtotime($sem['end_date'])) ?></td>
                                    <td>
                                        <?php if ($sem['is_active']): ?>
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Không hoạt động</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$sem['is_active']): ?>
                                        <a href="<?= BASE_URL ?>/admin/activateSemester/<?= $sem['semester_id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Kích hoạt học kỳ này?')"><i class="bi bi-check-circle"></i></a>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $sem['semester_id'] ?>"><i class="bi bi-pencil"></i></button>
                                        <?php if (!$sem['is_active']): ?>
                                        <a href="<?= BASE_URL ?>/admin/deleteSemester/<?= $sem['semester_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa học kỳ này?')"><i class="bi bi-trash"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center text-muted">Chưa có học kỳ nào</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm -->
<div class="modal fade" id="addSemesterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Thêm học kỳ mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/createSemester">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên học kỳ *</label>
                        <input type="text" name="name" class="form-control" placeholder="VD: Học kỳ 1 năm 2024-2025" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Năm học *</label>
                            <input type="text" name="academic_year" class="form-control" placeholder="2024-2025" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Học kỳ *</label>
                            <select name="semester_number" class="form-select" required>
                                <option value="1">Học kỳ 1</option>
                                <option value="2">Học kỳ 2</option>
                                <option value="3">Học kỳ hè</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày bắt đầu *</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày kết thúc *</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActiveNew">
                        <label class="form-check-label" for="isActiveNew">Kích hoạt ngay</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo học kỳ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa -->
<?php if (!empty($data['semesters'])): foreach ($data['semesters'] as $sem): ?>
<div class="modal fade" id="editModal<?= $sem['semester_id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Sửa học kỳ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/updateSemester/<?= $sem['semester_id'] ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên học kỳ *</label>
                        <input type="text" name="name" class="form-control" value="<?= $sem['name'] ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Năm học *</label>
                            <input type="text" name="academic_year" class="form-control" value="<?= $sem['academic_year'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Học kỳ *</label>
                            <select name="semester_number" class="form-select" required>
                                <option value="1" <?= $sem['semester_number']==1?'selected':'' ?>>Học kỳ 1</option>
                                <option value="2" <?= $sem['semester_number']==2?'selected':'' ?>>Học kỳ 2</option>
                                <option value="3" <?= $sem['semester_number']==3?'selected':'' ?>>Học kỳ hè</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày bắt đầu *</label>
                            <input type="date" name="start_date" class="form-control" value="<?= $sem['start_date'] ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày kết thúc *</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $sem['end_date'] ?>" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" <?= $sem['is_active']?'checked':'' ?>>
                        <label class="form-check-label">Đang hoạt động</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
