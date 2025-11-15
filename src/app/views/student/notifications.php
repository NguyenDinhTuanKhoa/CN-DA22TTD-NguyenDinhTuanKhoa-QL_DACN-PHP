<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Thông báo từ giảng viên</h2>
            
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($data['notifications'])): ?>
                        <div class="list-group">
                            <?php foreach ($data['notifications'] as $notif): ?>
                                <div class="list-group-item <?= $notif['is_read'] ? '' : 'list-group-item-primary' ?>">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-bell-fill text-primary me-2"></i>
                                                <h5 class="mb-0"><?= $notif['title'] ?></h5>
                                                <?php if (!$notif['is_read']): ?>
                                                    <span class="badge bg-danger ms-2">Mới</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="mb-2"><?= nl2br($notif['content']) ?></p>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> <?= $notif['sender_name'] ?? 'Hệ thống' ?> • 
                                                <i class="bi bi-clock"></i> <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-3">Chưa có thông báo nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
