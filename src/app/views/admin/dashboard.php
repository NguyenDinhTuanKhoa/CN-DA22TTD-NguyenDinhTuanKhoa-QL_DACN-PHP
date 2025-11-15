<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Dashboard - Tổng quan hệ thống</h2>
            
            <!-- Thống kê tổng quan -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Sinh viên</h6>
                                    <h2 class="mb-0"><?= $data['total_students'] ?></h2>
                                </div>
                                <i class="bi bi-people-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Giảng viên</h6>
                                    <h2 class="mb-0"><?= $data['total_teachers'] ?></h2>
                                </div>
                                <i class="bi bi-person-badge-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Đề tài</h6>
                                    <h2 class="mb-0"><?= $data['total_topics'] ?></h2>
                                </div>
                                <i class="bi bi-journal-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title">Đăng ký</h6>
                                    <h2 class="mb-0"><?= $data['topic_stats']['total_registrations'] ?? 0 ?></h2>
                                </div>
                                <i class="bi bi-clipboard-check-fill fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Biểu đồ và thông tin chi tiết -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Trạng thái đề tài</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Đã duyệt</span>
                                    <span class="badge bg-success"><?= $data['topic_stats']['approved'] ?? 0 ?></span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: <?= $data['total_topics'] > 0 ? ($data['topic_stats']['approved'] / $data['total_topics'] * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Chờ duyệt</span>
                                    <span class="badge bg-warning"><?= $data['topic_stats']['pending'] ?? 0 ?></span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: <?= $data['total_topics'] > 0 ? ($data['topic_stats']['pending'] / $data['total_topics'] * 100) : 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Thông báo hệ thống</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill"></i> Hệ thống đang hoạt động bình thường
                            </div>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i> Cần thiết lập thời gian đăng ký đề tài
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
