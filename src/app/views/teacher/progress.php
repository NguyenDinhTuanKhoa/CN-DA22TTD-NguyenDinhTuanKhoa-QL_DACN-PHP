<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Theo dõi tiến độ sinh viên</h2>
            
            <!-- Thông tin sinh viên -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-person-fill"></i> Thông tin sinh viên</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> <?= htmlspecialchars($data['registration']['student_name']) ?></p>
                            <p><strong>Mã SV:</strong> <?= htmlspecialchars($data['registration']['student_code']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <?= htmlspecialchars($data['registration']['student_email']) ?></p>
                            <p><strong>Đề tài:</strong> <?= htmlspecialchars($data['registration']['topic_title']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Báo cáo tiến độ 4 tuần -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Báo cáo tiến độ 4 tuần</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($data['reports'])): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Sinh viên chưa báo cáo tiến độ
                        </div>
                    <?php else: ?>
                        <?php for ($week = 1; $week <= 4; $week++): ?>
                            <h6 class="mt-3">Tuần <?= $week ?></h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Công việc</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú</th>
                                            <th>Cập nhật</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $weekReports = array_filter($data['reports'], function($r) use ($week) {
                                            return $r['week_number'] == $week;
                                        });
                                        ?>
                                        <?php if (empty($weekReports)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Chưa có báo cáo</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($weekReports as $report): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($report['task_name']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $report['status'] === 'completed' ? 'success' : 'warning' ?>">
                                                        <?= $report['status'] === 'completed' ? 'Hoàn thành' : 'Chưa hoàn thành' ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($report['note'] ?? '-') ?></td>
                                                <td><?= date('d/m/Y H:i', strtotime($report['updated_at'])) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Bài nộp -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload"></i> Bài nộp của sinh viên</h5>
                </div>
                <div class="card-body">
                    <?php if (!$data['submission']): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Sinh viên chưa nộp bài
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="bi bi-google"></i> Google Drive</h6>
                                        <a href="<?= htmlspecialchars($data['submission']['google_drive_link']) ?>" 
                                           target="_blank" 
                                           class="btn btn-primary btn-sm">
                                            <i class="bi bi-box-arrow-up-right"></i> Xem báo cáo, video
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="bi bi-github"></i> GitHub</h6>
                                        <a href="<?= htmlspecialchars($data['submission']['github_link']) ?>" 
                                           target="_blank" 
                                           class="btn btn-dark btn-sm">
                                            <i class="bi bi-box-arrow-up-right"></i> Xem mã nguồn
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($data['submission']['note']): ?>
                            <div class="mt-3">
                                <strong>Ghi chú từ sinh viên:</strong>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($data['submission']['note'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <hr>
                        <small class="text-muted">
                            <i class="bi bi-clock"></i> Nộp lúc: <?= date('d/m/Y H:i', strtotime($data['submission']['submitted_at'])) ?>
                        </small>
                        
                        <!-- Form góp ý -->
                        <div class="mt-4">
                            <h6>Góp ý cho sinh viên</h6>
                            <form method="POST" action="/teacher/sendNotification">
                                <input type="hidden" name="student_id" value="<?= $data['registration']['student_id'] ?>">
                                <div class="mb-3">
                                    <input type="text" name="title" class="form-control" placeholder="Tiêu đề góp ý" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="4" placeholder="Nội dung góp ý..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-send"></i> Gửi góp ý
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-3">
                <a href="/teacher/students" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>