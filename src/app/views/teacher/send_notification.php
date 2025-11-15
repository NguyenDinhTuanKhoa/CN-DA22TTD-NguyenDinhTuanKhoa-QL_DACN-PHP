<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Gửi thông báo</h2>
                <a href="/PHP-CN/public/teacher/students" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-bell-fill"></i> Tạo thông báo mới</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/PHP-CN/public/teacher/sendNotification">
                                <div class="mb-3">
                                    <label class="form-label">Gửi đến <span class="text-danger">*</span></label>
                                    <select name="recipient_type" id="recipientType" class="form-select" required>
                                        <option value="">-- Chọn người nhận --</option>
                                        <option value="all">Tất cả sinh viên của tôi</option>
                                        <option value="single">Sinh viên cụ thể</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3" id="studentSelect" style="display: none;">
                                    <label class="form-label">Chọn sinh viên</label>
                                    <select name="student_id" class="form-select">
                                        <option value="">-- Chọn sinh viên --</option>
                                        <?php foreach ($data['students'] as $student): ?>
                                            <option value="<?= $student['student_id'] ?>">
                                                <?= $student['student_name'] ?> (<?= $student['student_code'] ?>) - <?= $student['topic_title'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề thông báo" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control" rows="6" placeholder="Nhập nội dung thông báo..." required></textarea>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send-fill"></i> Gửi thông báo
                                    </button>
                                    <a href="/PHP-CN/public/teacher/students" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Hủy
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Hướng dẫn</h5>
                        </div>
                        <div class="card-body">
                            <h6>Gửi thông báo:</h6>
                            <ul class="small">
                                <li><strong>Tất cả:</strong> Gửi đến tất cả sinh viên đang thực hiện đề tài của bạn</li>
                                <li><strong>Cụ thể:</strong> Gửi đến một sinh viên cụ thể</li>
                            </ul>
                            
                            <hr>
                            
                            <h6>Lưu ý:</h6>
                            <ul class="small mb-0">
                                <li>Sinh viên sẽ nhận được thông báo ngay lập tức</li>
                                <li>Thông báo sẽ hiển thị trên trang chủ của sinh viên</li>
                                <li>Nên viết nội dung rõ ràng, súc tích</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-people-fill"></i> Sinh viên của tôi</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Tổng số:</strong> <?= count($data['students']) ?> sinh viên</p>
                            <?php if (!empty($data['students'])): ?>
                                <ul class="list-unstyled small mb-0">
                                    <?php foreach (array_slice($data['students'], 0, 5) as $student): ?>
                                        <li class="mb-1">
                                            <i class="bi bi-person-fill text-primary"></i> 
                                            <?= $student['student_name'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                    <?php if (count($data['students']) > 5): ?>
                                        <li class="text-muted">... và <?= count($data['students']) - 5 ?> sinh viên khác</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('recipientType').addEventListener('change', function() {
    const studentSelect = document.getElementById('studentSelect');
    const studentSelectInput = studentSelect.querySelector('select');
    
    if (this.value === 'single') {
        studentSelect.style.display = 'block';
        studentSelectInput.required = true;
    } else {
        studentSelect.style.display = 'none';
        studentSelectInput.required = false;
        studentSelectInput.value = '';
    }
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
