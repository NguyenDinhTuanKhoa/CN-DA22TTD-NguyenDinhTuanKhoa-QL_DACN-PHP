<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Quản lý đăng ký sinh viên</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">
                        <i class="bi bi-clock-history"></i> Chờ duyệt
                        <?php 
                        $pendingCount = count(array_filter($data['registrations'], fn($r) => $r['status'] === 'pending'));
                        if ($pendingCount > 0): ?>
                            <span class="badge bg-warning"><?= $pendingCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#approved">
                        <i class="bi bi-check-circle"></i> Đã chấp nhận
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#rejected">
                        <i class="bi bi-x-circle"></i> Đã từ chối
                    </a>
                </li>
            </ul>
            
            <div class="tab-content">
                <!-- Tab Chờ duyệt -->
                <div class="tab-pane fade show active" id="pending">
                    <div class="card">
                        <div class="card-body">
                            <?php 
                            $pendingRegs = array_filter($data['registrations'], fn($r) => $r['status'] === 'pending');
                            if (!empty($pendingRegs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã SV</th>
                                                <th>Họ tên</th>
                                                <th>Email</th>
                                                <th>Đề tài</th>
                                                <th>Ngày đăng ký</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $idx = 0; foreach ($pendingRegs as $reg): $idx++; ?>
                                                <tr>
                                                    <td><?= $idx ?></td>
                                                    <td><strong><?= htmlspecialchars($reg['student_code']) ?></strong></td>
                                                    <td><?= htmlspecialchars($reg['student_name']) ?></td>
                                                    <td><?= htmlspecialchars($reg['student_email']) ?></td>
                                                    <td><?= htmlspecialchars($reg['topic_title']) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($reg['registered_at'])) ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success btn-approve" 
                                                                data-id="<?= $reg['registration_id'] ?>"
                                                                data-name="<?= htmlspecialchars($reg['student_name']) ?>"
                                                                data-code="<?= htmlspecialchars($reg['student_code']) ?>"
                                                                data-topic="<?= htmlspecialchars($reg['topic_title']) ?>">
                                                            <i class="bi bi-check-circle"></i> Chấp nhận
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-reject" 
                                                                data-id="<?= $reg['registration_id'] ?>"
                                                                data-name="<?= htmlspecialchars($reg['student_name']) ?>"
                                                                data-code="<?= htmlspecialchars($reg['student_code']) ?>"
                                                                data-topic="<?= htmlspecialchars($reg['topic_title']) ?>">
                                                            <i class="bi bi-x-circle"></i> Từ chối
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">Không có đăng ký nào đang chờ duyệt</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Đã chấp nhận -->
                <div class="tab-pane fade" id="approved">
                    <div class="card">
                        <div class="card-body">
                            <?php 
                            $approvedRegs = array_filter($data['registrations'], fn($r) => $r['status'] === 'approved');
                            if (!empty($approvedRegs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã SV</th>
                                                <th>Họ tên</th>
                                                <th>Email</th>
                                                <th>Đề tài</th>
                                                <th>Ngày đăng ký</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $idx = 0; foreach ($approvedRegs as $reg): $idx++; ?>
                                                <tr>
                                                    <td><?= $idx ?></td>
                                                    <td><strong><?= htmlspecialchars($reg['student_code']) ?></strong></td>
                                                    <td><?= htmlspecialchars($reg['student_name']) ?></td>
                                                    <td><?= htmlspecialchars($reg['student_email']) ?></td>
                                                    <td><?= htmlspecialchars($reg['topic_title']) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($reg['registered_at'])) ?></td>
                                                    <td>
                                                        <a href="<?= BASE_URL ?>/teacher/progress/<?= $reg['registration_id'] ?>" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i> Xem tiến độ
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">Chưa có sinh viên nào được chấp nhận</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Đã từ chối -->
                <div class="tab-pane fade" id="rejected">
                    <div class="card">
                        <div class="card-body">
                            <?php 
                            $rejectedRegs = array_filter($data['registrations'], fn($r) => $r['status'] === 'rejected');
                            if (!empty($rejectedRegs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã SV</th>
                                                <th>Họ tên</th>
                                                <th>Email</th>
                                                <th>Đề tài</th>
                                                <th>Ngày đăng ký</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $idx = 0; foreach ($rejectedRegs as $reg): $idx++; ?>
                                                <tr>
                                                    <td><?= $idx ?></td>
                                                    <td><strong><?= htmlspecialchars($reg['student_code']) ?></strong></td>
                                                    <td><?= htmlspecialchars($reg['student_name']) ?></td>
                                                    <td><?= htmlspecialchars($reg['student_email']) ?></td>
                                                    <td><?= htmlspecialchars($reg['topic_title']) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($reg['registered_at'])) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted mt-3">Chưa có đăng ký nào bị từ chối</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chấp nhận (Dùng chung) -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Xác nhận chấp nhận</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn chấp nhận sinh viên:</p>
                <div class="alert alert-info">
                    <p class="mb-1"><strong>Sinh viên:</strong> <span id="approveStudentName"></span> (<span id="approveStudentCode"></span>)</p>
                    <p class="mb-0"><strong>Đề tài:</strong> <span id="approveTopicTitle"></span></p>
                </div>
                <p class="text-muted small">Sinh viên sẽ nhận được thông báo qua hệ thống.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="approveLink" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Xác nhận chấp nhận
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Từ chối (Dùng chung) -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận từ chối</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn từ chối sinh viên:</p>
                <div class="alert alert-warning">
                    <p class="mb-1"><strong>Sinh viên:</strong> <span id="rejectStudentName"></span> (<span id="rejectStudentCode"></span>)</p>
                    <p class="mb-0"><strong>Đề tài:</strong> <span id="rejectTopicTitle"></span></p>
                </div>
                <p class="text-muted small">Sinh viên sẽ nhận được thông báo và có thể đăng ký đề tài khác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="rejectLink" class="btn btn-danger">
                    <i class="bi bi-x-circle"></i> Xác nhận từ chối
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý nút Chấp nhận
document.querySelectorAll('.btn-approve').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const code = this.dataset.code;
        const topic = this.dataset.topic;
        
        document.getElementById('approveStudentName').textContent = name;
        document.getElementById('approveStudentCode').textContent = code;
        document.getElementById('approveTopicTitle').textContent = topic;
        document.getElementById('approveLink').href = '<?= BASE_URL ?>/teacher/approveRegistration/' + id;
        
        new bootstrap.Modal(document.getElementById('approveModal')).show();
    });
});

// Xử lý nút Từ chối
document.querySelectorAll('.btn-reject').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        const code = this.dataset.code;
        const topic = this.dataset.topic;
        
        document.getElementById('rejectStudentName').textContent = name;
        document.getElementById('rejectStudentCode').textContent = code;
        document.getElementById('rejectTopicTitle').textContent = topic;
        document.getElementById('rejectLink').href = '<?= BASE_URL ?>/teacher/rejectRegistration/' + id;
        
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    });
});

// Loading khi click xác nhận
document.querySelectorAll('#approveLink, #rejectLink').forEach(link => {
    link.addEventListener('click', function() {
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang xử lý...';
        this.classList.add('disabled');
    });
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
