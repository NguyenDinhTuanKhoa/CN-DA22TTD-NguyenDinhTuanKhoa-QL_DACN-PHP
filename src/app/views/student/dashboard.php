<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Trang chủ sinh viên</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Trạng thái đăng ký -->
            <div class="row g-4 mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle-fill"></i> Trạng thái đăng ký đề tài</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($data['my_registration']): ?>
                                <div class="alert alert-success">
                                    <h5><i class="bi bi-check-circle-fill"></i> Bạn đã đăng ký đề tài</h5>
                                    <hr>
                                    <p class="mb-1"><strong>Đề tài:</strong> <?= $data['my_registration']['topic_title'] ?></p>
                                    <p class="mb-1"><strong>Giảng viên:</strong> <?= $data['my_registration']['teacher_name'] ?></p>
                                    <p class="mb-1"><strong>Trạng thái:</strong> 
                                        <?php if ($data['my_registration']['status'] === 'approved'): ?>
                                            <span class="badge bg-success">Đã duyệt</span>
                                        <?php elseif ($data['my_registration']['status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Từ chối</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mb-0"><strong>Ngày đăng ký:</strong> <?= date('d/m/Y', strtotime($data['my_registration']['created_at'])) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i> Bạn chưa đăng ký đề tài nào.
                                    <a href="/PHP-CN/public/student/topics" class="btn btn-primary btn-sm ms-3">
                                        <i class="bi bi-plus-circle"></i> Đăng ký ngay
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Thông báo mới nhất -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-bell-fill"></i> Thông báo mới nhất</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['notifications'])): ?>
                                <div class="list-group">
                                    <?php foreach ($data['notifications'] as $notif): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-1"><?= $notif['title'] ?></h6>
                                                <small><?= date('d/m/Y', strtotime($notif['created_at'])) ?></small>
                                            </div>
                                            <p class="mb-0 text-muted small"><?= substr($notif['content'] ?? '', 0, 100) ?>...</p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <a href="/PHP-CN/public/student/notifications" class="btn btn-sm btn-outline-info mt-3">
                                    Xem tất cả →
                                </a>
                            <?php else: ?>
                                <p class="text-muted">Chưa có thông báo nào.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-list-check"></i> Hướng dẫn sử dụng</h5>
                        </div>
                        <div class="card-body">
                            <ol class="mb-0">
                                <li class="mb-2">Xem danh sách đề tài và chọn đề tài phù hợp</li>
                                <li class="mb-2">Đăng ký đề tài trong thời gian quy định</li>
                                <li class="mb-2">Cập nhật báo cáo tiến độ định kỳ (4 tuần)</li>
                                <li class="mb-2">Theo dõi thông báo từ giảng viên</li>
                                <li class="mb-2">Nộp bài đồ án qua Google Drive và GitHub</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
