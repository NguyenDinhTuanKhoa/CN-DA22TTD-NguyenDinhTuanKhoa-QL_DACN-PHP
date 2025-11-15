<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Dashboard - Tổng quan</h2>
            
            <!-- Thống kê -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Đề tài của tôi</h6>
                                    <h2 class="mb-0"><?= $data['total_topics'] ?></h2>
                                </div>
                                <i class="bi bi-journal-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Sinh viên hướng dẫn</h6>
                                    <h2 class="mb-0"><?= $data['total_students'] ?></h2>
                                </div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Thông báo</h6>
                                    <h2 class="mb-0">0</h2>
                                </div>
                                <i class="bi bi-bell-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Đề tài gần đây -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Đề tài của tôi</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['topics'])): ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> Bạn chưa có đề tài nào. 
                                    <a href="/teacher/createTopic" class="alert-link">Tạo đề tài mới</a>
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach (array_slice($data['topics'], 0, 5) as $topic): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($topic['title']) ?></h6>
                                                    <small class="text-muted">
                                                        Đã đăng ký: <?= $topic['current_students'] ?>/<?= $topic['max_students'] ?>
                                                    </small>
                                                </div>
                                                <span class="badge bg-<?= $topic['status'] === 'approved' ? 'success' : 'warning' ?>">
                                                    <?= $topic['status'] === 'approved' ? 'Đã duyệt' : 'Chờ duyệt' ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Sinh viên đăng ký gần đây</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($data['registrations'])): ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i> Chưa có sinh viên đăng ký
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach (array_slice($data['registrations'], 0, 5) as $reg): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($reg['student_name']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($reg['topic_title']) ?></small>
                                                </div>
                                                <span class="badge bg-<?= $reg['status'] === 'approved' ? 'success' : 'warning' ?>">
                                                    <?= $reg['status'] === 'approved' ? 'Đã duyệt' : 'Chờ duyệt' ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>