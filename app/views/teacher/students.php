<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Sinh viên hướng dẫn</h2>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã SV</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Đề tài</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['registrations'])): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            Chưa có sinh viên nào đăng ký
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['registrations'] as $index => $reg): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($reg['student_code']) ?></strong></td>
                                        <td><?= htmlspecialchars($reg['student_name']) ?></td>
                                        <td><?= htmlspecialchars($reg['student_email']) ?></td>
                                        <td><?= htmlspecialchars($reg['topic_title']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $reg['status'] === 'approved' ? 'success' : 'warning' ?>">
                                                <?= $reg['status'] === 'approved' ? 'Đã duyệt' : 'Chờ duyệt' ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($reg['registered_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#notifyModal<?= $reg['registration_id'] ?>">
                                                <i class="bi bi-bell"></i> Thông báo
                                            </button>
                                            <a href="/PHP-CN/public/teacher/progress/<?= $reg['registration_id'] ?>" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-graph-up"></i> Tiến độ
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal thông báo -->
                                    <div class="modal fade" id="notifyModal<?= $reg['registration_id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Gửi thông báo đến <?= htmlspecialchars($reg['student_name']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="/PHP-CN/public/teacher/sendNotification">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="student_id" value="<?= $reg['student_id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tiêu đề</label>
                                                            <input type="text" name="title" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Nội dung</label>
                                                            <textarea name="content" class="form-control" rows="4" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-primary">Gửi thông báo</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>