<?php include_once __DIR__ . '/../../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-chat-dots"></i> Nhóm chat hướng dẫn</h2>
            
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-chat-dots" style="font-size: 64px; color: #dee2e6;"></i>
                    <p class="text-muted mt-3 mb-0"><?= $data['message'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layouts/footer.php'; ?>
