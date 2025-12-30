<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Danh sách đề tài</h2>
            
            <?php if (!$data['can_register']): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Chức năng đăng ký đề tài hiện đang bị khóa!</strong>
                    <?php if ($data['registration_time']): ?>
                        <br>Thời gian đăng ký: 
                        <?= date('d/m/Y H:i', strtotime($data['registration_time']['start_date'])) ?> - 
                        <?= date('d/m/Y H:i', strtotime($data['registration_time']['end_date'])) ?>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php if ($data['registration_time']): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Sinh viên có thể đăng ký đề tài trong khoảng thời gian này
                        <br><strong>Bắt đầu:</strong> <?= date('d/m/Y H:i', strtotime($data['registration_time']['start_date'])) ?>
                        <br><strong>Kết thúc:</strong> <?= date('d/m/Y H:i', strtotime($data['registration_time']['end_date'])) ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Thanh tìm kiếm -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="searchTopic" placeholder="Tìm theo tên đề tài...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="searchTeacher" placeholder="Tìm theo giảng viên...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterStatus">
                                <option value="">Tất cả trạng thái</option>
                                <option value="available">Còn chỗ</option>
                                <option value="full">Đã đủ</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="topicsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên đề tài</th>
                                    <th>Giảng viên</th>
                                    <th>Mô tả</th>
                                    <th>Yêu cầu</th>
                                    <th>Số lượng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['topics'])): ?>
                                    <?php foreach ($data['topics'] as $index => $topic): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= $topic['title'] ?></strong></td>
                                            <td><?= $topic['teacher_name'] ?></td>
                                            <td><?= substr($topic['description'], 0, 100) ?>...</td>
                                            <td><?= substr($topic['requirements'], 0, 80) ?>...</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= $topic['current_students'] ?>/<?= $topic['max_students'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($topic['status'] === 'approved'): ?>
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($topic['current_students'] < $topic['max_students'] && $topic['status'] === 'approved' && $data['can_register']): ?>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal<?= $topic['topic_id'] ?>">
                                                        <i class="bi bi-plus-circle"></i> Đăng ký
                                                    </button>
                                                <?php elseif (!$data['can_register']): ?>
                                                    <button class="btn btn-sm btn-secondary" disabled title="Chức năng đăng ký đang bị khóa">
                                                        <i class="bi bi-lock"></i> Đã khóa
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-secondary" disabled>
                                                        Đã đủ
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $topic['topic_id'] ?>">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Chưa có đề tài nào</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals Section -->
<?php if (!empty($data['topics'])): ?>
    <?php foreach ($data['topics'] as $topic): ?>
        <!-- Modal đăng ký -->
        <div class="modal" id="registerModal<?= $topic['topic_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận đăng ký</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn đăng ký đề tài:</p>
                        <p class="fw-bold"><?= $topic['title'] ?></p>
                        <p>Giảng viên: <?= $topic['teacher_name'] ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form method="POST" action="/PHP-CN/public/student/register/<?= $topic['topic_id'] ?>" style="display: inline;">
                            <button type="submit" class="btn btn-primary">Xác nhận đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal chi tiết -->
        <div class="modal" id="detailModal<?= $topic['topic_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chi tiết đề tài</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Tên đề tài:</h6>
                        <p><?= $topic['title'] ?></p>
                        
                        <h6>Giảng viên hướng dẫn:</h6>
                        <p><?= $topic['teacher_name'] ?></p>
                        
                        <h6>Mô tả:</h6>
                        <p><?= $topic['description'] ?></p>
                        
                        <h6>Yêu cầu:</h6>
                        <p><?= $topic['requirements'] ?></p>
                        
                        <h6>Số lượng sinh viên:</h6>
                        <p><?= $topic['current_students'] ?>/<?= $topic['max_students'] ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchTopic = document.getElementById('searchTopic');
    const searchTeacher = document.getElementById('searchTeacher');
    const filterStatus = document.getElementById('filterStatus');
    const table = document.getElementById('topicsTable');
    const rows = table.querySelectorAll('tbody tr');
    
    function filterTable() {
        const topicValue = searchTopic.value.toLowerCase();
        const teacherValue = searchTeacher.value.toLowerCase();
        const statusValue = filterStatus.value;
        
        rows.forEach(row => {
            const topicName = row.cells[1]?.textContent.toLowerCase() || '';
            const teacherName = row.cells[2]?.textContent.toLowerCase() || '';
            const slots = row.cells[5]?.textContent || '';
            
            // Parse slots (e.g., "1/2")
            const slotMatch = slots.match(/(\d+)\/(\d+)/);
            let isAvailable = true;
            if (slotMatch) {
                isAvailable = parseInt(slotMatch[1]) < parseInt(slotMatch[2]);
            }
            
            const matchTopic = topicName.includes(topicValue);
            const matchTeacher = teacherName.includes(teacherValue);
            let matchStatus = true;
            
            if (statusValue === 'available') {
                matchStatus = isAvailable;
            } else if (statusValue === 'full') {
                matchStatus = !isAvailable;
            }
            
            row.style.display = (matchTopic && matchTeacher && matchStatus) ? '' : 'none';
        });
    }
    
    searchTopic.addEventListener('input', filterTable);
    searchTeacher.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);
});
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
