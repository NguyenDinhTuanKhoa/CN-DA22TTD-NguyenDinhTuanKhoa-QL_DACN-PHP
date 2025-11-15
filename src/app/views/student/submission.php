<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Nộp bài đồ án</h2>
            
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
                
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-cloud-upload-fill"></i> 
                            <?= $data['submission'] ? 'Cập nhật bài nộp' : 'Nộp bài đồ án' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/PHP-CN/public/student/submitProject">
                            <input type="hidden" name="registration_id" value="<?= $data['registration']['registration_id'] ?>">
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle-fill"></i> 
                                <strong>Hướng dẫn nộp bài:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Tải tài liệu (báo cáo, video demo) lên Google Drive và chia sẻ link</li>
                                    <li>Tải mã nguồn lên GitHub và chia sẻ link repository</li>
                                    <li>Đảm bảo các link có quyền truy cập công khai</li>
                                </ul>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-google"></i> Link Google Drive 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="url" 
                                       name="drive_link" 
                                       class="form-control" 
                                       placeholder="https://drive.google.com/..." 
                                       value="<?= $data['submission']['google_drive_link'] ?? '' ?>"
                                       required>
                                <small class="text-muted">Link chứa báo cáo, video demo, tài liệu</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-github"></i> Link GitHub Repository 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="url" 
                                       name="github_link" 
                                       class="form-control" 
                                       placeholder="https://github.com/..." 
                                       value="<?= $data['submission']['github_link'] ?? '' ?>"
                                       required>
                                <small class="text-muted">Link repository chứa mã nguồn</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="notes" 
                                          class="form-control" 
                                          rows="4" 
                                          placeholder="Ghi chú thêm về bài nộp (nếu có)..."><?= $data['submission']['note'] ?? '' ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-cloud-upload-fill"></i> 
                                <?= $data['submission'] ? 'Cập nhật bài nộp' : 'Nộp bài' ?>
                            </button>
                        </form>
                        
                        <?php if ($data['submission']): ?>
                            <hr class="my-4">
                            <div class="alert alert-success">
                                <h6><i class="bi bi-check-circle-fill"></i> Thông tin bài đã nộp</h6>
                                <p class="mb-1"><strong>Google Drive:</strong> <a href="<?= $data['submission']['google_drive_link'] ?>" target="_blank"><?= $data['submission']['google_drive_link'] ?></a></p>
                                <p class="mb-1"><strong>GitHub:</strong> <a href="<?= $data['submission']['github_link'] ?>" target="_blank"><?= $data['submission']['github_link'] ?></a></p>
                                <p class="mb-1"><strong>Ngày nộp:</strong> <?= date('d/m/Y H:i', strtotime($data['submission']['submitted_at'])) ?></p>
                                <?php if ($data['submission']['note']): ?>
                                    <p class="mb-0"><strong>Ghi chú:</strong> <?= $data['submission']['note'] ?></p>
                                <?php endif; ?>
                            </div>
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
