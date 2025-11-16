<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Cài đặt thời gian</h2>
                <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addForm">
                    <i class="bi bi-plus-circle"></i> Thêm cài đặt mới
                </button>
            </div>
            
            <!-- Form thêm mới -->
            <div class="collapse mb-4" id="addForm">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thêm cài đặt thời gian mới</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/PHP-CN/public/admin/createTimeSetting">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loại cài đặt <span class="text-danger">*</span></label>
                                    <select class="form-select" name="setting_type" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="topic_creation">Ra đề tài</option>
                                        <option value="topic_registration">Đăng ký đề tài</option>
                                        <option value="progress_report">Báo cáo tiến độ</option>
                                        <option value="submission">Nộp bài</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên cài đặt <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="setting_name" 
                                           placeholder="VD: Thời gian ra đề tài học kỳ 1" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="2" 
                                          placeholder="Mô tả chi tiết về cài đặt này..."></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="start_date" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="end_date" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="auto_lock" value="1" 
                                               id="auto_lock_new" checked>
                                        <label class="form-check-label" for="auto_lock_new">
                                            <i class="bi bi-lock-fill"></i> Tự động khóa khi hết hạn
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                                               id="is_active_new" checked>
                                        <label class="form-check-label" for="is_active_new">
                                            <i class="bi bi-check-circle-fill"></i> Kích hoạt ngay
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Lưu cài đặt
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addForm">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Tên cài đặt</th>
                                    <th width="15%">Loại</th>
                                    <th width="15%">Bắt đầu</th>
                                    <th width="15%">Kết thúc</th>
                                    <th width="10%">Trạng thái</th>
                                    <th width="10%">Auto Lock</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['settings'])): ?>
                                    <?php foreach ($data['settings'] as $setting): ?>
                                        <?php
                                        $now = new DateTime();
                                        $startDate = new DateTime($setting['start_date']);
                                        $endDate = new DateTime($setting['end_date']);
                                        
                                        if ($setting['is_active'] == 0) {
                                            $status = '<span class="badge bg-danger">Đã khóa</span>';
                                        } elseif ($now < $startDate) {
                                            $status = '<span class="badge bg-secondary">Chưa bắt đầu</span>';
                                        } elseif ($now > $endDate) {
                                            $status = '<span class="badge bg-dark">Đã hết hạn</span>';
                                        } else {
                                            $status = '<span class="badge bg-success">Đang hoạt động</span>';
                                        }
                                        
                                        $typeNames = [
                                            'topic_creation' => 'Ra đề tài',
                                            'topic_registration' => 'Đăng ký đề tài',
                                            'progress_report' => 'Báo cáo tiến độ',
                                            'submission' => 'Nộp bài'
                                        ];
                                        ?>
                                        <tr>
                                            <td><?= $setting['setting_id'] ?></td>
                                            <td><strong><?= htmlspecialchars($setting['setting_name']) ?></strong></td>
                                            <td><?= $typeNames[$setting['setting_type']] ?? $setting['setting_type'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($setting['start_date'])) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($setting['end_date'])) ?></td>
                                            <td><?= $status ?></td>
                                            <td>
                                                <?php if ($setting['auto_lock']): ?>
                                                    <span class="badge bg-info">Có</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Không</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $setting['setting_id'] ?>">
                                                    <i class="bi bi-pencil"></i> Sửa
                                                </button>
                                                <a href="/PHP-CN/public/admin/toggleTimeSetting/<?= $setting['setting_id'] ?>" 
                                                   class="btn btn-sm btn-<?= $setting['is_active'] ? 'warning' : 'success' ?>"
                                                   onclick="return confirm('Bạn có chắc muốn <?= $setting['is_active'] ? 'KHÓA' : 'MỞ KHÓA' ?> chức năng này?')">
                                                    <i class="bi bi-<?= $setting['is_active'] ? 'lock' : 'unlock' ?>"></i>
                                                    <?= $setting['is_active'] ? 'Khóa' : 'Mở' ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Chưa có cài đặt thời gian nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted mb-2"><strong>Chú thích:</strong></p>
                        <ul class="text-muted small">
                            <li><strong>Ra đề tài:</strong> Giảng viên có thể tạo đề tài</li>
                            <li><strong>Đăng ký đề tài:</strong> Sinh viên có thể đăng ký đề tài</li>
                            <li><strong>Báo cáo tiến độ:</strong> Sinh viên cập nhật tiến độ</li>
                            <li><strong>Nộp bài:</strong> Sinh viên nộp bài cuối kỳ</li>
                            <li><strong>Auto Lock:</strong> Tự động khóa khi hết thời gian</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals sửa -->
<?php if (!empty($data['settings'])): ?>
    <?php foreach ($data['settings'] as $setting): ?>
        <div class="modal fade" id="editModal<?= $setting['setting_id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="/PHP-CN/public/admin/editTimeSetting/<?= $setting['setting_id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa: <?= htmlspecialchars($setting['setting_name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="setting_type" value="<?= $setting['setting_type'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Tên cài đặt</label>
                                <input type="text" class="form-control" name="setting_name" 
                                       value="<?= htmlspecialchars($setting['setting_name']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($setting['description']) ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bắt đầu</label>
                                    <input type="datetime-local" class="form-control" name="start_date" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($setting['start_date'])) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kết thúc</label>
                                    <input type="datetime-local" class="form-control" name="end_date" 
                                           value="<?= date('Y-m-d\TH:i', strtotime($setting['end_date'])) ?>" required>
                                </div>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Lưu ý:</strong> Nếu bật "Tự động khóa" và thời gian đã hết hạn, 
                                chức năng sẽ bị khóa tự động dù bạn có bấm "Mở".
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="auto_lock" value="1" 
                                       id="auto_lock_<?= $setting['setting_id'] ?>" <?= $setting['auto_lock'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="auto_lock_<?= $setting['setting_id'] ?>">
                                    Tự động khóa khi hết hạn
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_active" value="1" 
                                       id="is_active_<?= $setting['setting_id'] ?>" <?= $setting['is_active'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_active_<?= $setting['setting_id'] ?>">
                                    Kích hoạt
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
