<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Cài đặt thời gian</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTimeModal">
                    <i class="bi bi-plus-circle"></i> Thêm cài đặt
                </button>
            </div>
            
            <div class="row g-4">
                <?php foreach ($data['settings'] as $setting): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?= htmlspecialchars($setting['setting_name']) ?></h5>
                            <span class="badge bg-<?= $setting['is_active'] ? 'success' : 'secondary' ?>">
                                <?= $setting['is_active'] ? 'Đang hoạt động' : 'Không hoạt động' ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="text-muted"><?= htmlspecialchars($setting['description']) ?></p>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Bắt đầu:</small>
                                    <p class="mb-0"><strong><?= date('d/m/Y H:i', strtotime($setting['start_date'])) ?></strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Kết thúc:</small>
                                    <p class="mb-0"><strong><?= date('d/m/Y H:i', strtotime($setting['end_date'])) ?></strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <button class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Sửa
                            </button>
                            <button class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
