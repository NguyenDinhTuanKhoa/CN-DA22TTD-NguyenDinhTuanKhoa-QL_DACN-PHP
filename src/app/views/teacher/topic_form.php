<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4"><?= $data['action'] === 'create' ? 'Tạo đề tài mới' : 'Chỉnh sửa đề tài' ?></h2>
            
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên đề tài <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" 
                                   value="<?= $data['topic']['title'] ?? '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả đề tài <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="5" required><?= $data['topic']['description'] ?? '' ?></textarea>
                            <small class="text-muted">Mô tả chi tiết về đề tài, mục tiêu, phạm vi...</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Yêu cầu <span class="text-danger">*</span></label>
                            <textarea name="requirements" class="form-control" rows="4" required><?= $data['topic']['requirements'] ?? '' ?></textarea>
                            <small class="text-muted">Yêu cầu về kiến thức, kỹ năng cần có...</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số sinh viên tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="max_students" class="form-control" 
                                   value="<?= $data['topic']['max_students'] ?? 1 ?>" min="1" max="5" required>
                            <small class="text-muted">Số lượng sinh viên có thể đăng ký đề tài này (1-5)</small>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> <?= $data['action'] === 'create' ? 'Tạo đề tài' : 'Cập nhật' ?>
                            </button>
                            <a href="/PHP-CN/public/teacher/topics" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>