<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Báo cáo tiến độ đồ án</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($data['registration']): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-journal-text"></i> Thông tin đề tài</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Đề tài:</strong> <?= $data['registration']['topic_title'] ?></p>
                        <p><strong>Giảng viên:</strong> <?= $data['registration']['teacher_name'] ?></p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Thêm báo cáo tiến độ</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/PHP-CN/public/student/addProgress">
                            <input type="hidden" name="registration_id" value="<?= $data['registration']['registration_id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tuần thứ <span class="text-danger">*</span></label>
                                    <select name="week_number" class="form-select" required>
                                        <option value="">-- Chọn tuần --</option>
                                        <option value="1">Tuần 1</option>
                                        <option value="2">Tuần 2</option>
                                        <option value="3">Tuần 3</option>
                                        <option value="4">Tuần 4</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="completed">Đã hoàn thành</option>
                                        <option value="incomplete">Chưa hoàn thành</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tên công việc <span class="text-danger">*</span></label>
                                <input type="text" name="task_name" class="form-control" placeholder="Ví dụ: Phân tích yêu cầu hệ thống" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Mô tả chi tiết công việc đã thực hiện..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Thêm báo cáo
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-list-check"></i> Lịch sử báo cáo tiến độ</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['progress_reports'])): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tuần</th>
                                            <th>Tên công việc</th>
                                            <th>Mô tả</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày báo cáo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['progress_reports'] as $report): ?>
                                            <tr>
                                                <td><span class="badge bg-primary">Tuần <?= $report['week_number'] ?></span></td>
                                                <td><strong><?= $report['task_name'] ?></strong></td>
                                                <td><?= $report['note'] ?? 'Không có mô tả' ?></td>
                                                <td>
                                                    <?php if ($report['status'] === 'completed'): ?>
                                                        <span class="badge bg-success">Đã hoàn thành</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Chưa hoàn thành</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($report['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">Chưa có báo cáo tiến độ nào.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i> Bạn chưa đăng ký đề tài nào. 
                    <a href="/PHP-CN/public/student/topics" class="alert-link">Đăng ký ngay</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
