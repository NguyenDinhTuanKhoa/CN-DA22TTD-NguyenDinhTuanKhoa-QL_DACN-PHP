<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Quản lý đề tài</h2>
            
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
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đề tài</th>
                                    <th>Giảng viên</th>
                                    <th>Số SV tối đa</th>
                                    <th>Đã đăng ký</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['topics'] as $topic): ?>
                                <tr>
                                    <td><?= $topic['topic_id'] ?></td>
                                    <td><?= htmlspecialchars($topic['title']) ?></td>
                                    <td><?= htmlspecialchars($topic['teacher_name']) ?></td>
                                    <td><?= $topic['max_students'] ?></td>
                                    <td><?= $topic['current_students'] ?></td>
                                    <td>
                                        <?php
                                        $statusBadges = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                        $statusNames = [
                                            'pending' => 'Chờ duyệt',
                                            'approved' => 'Đã duyệt',
                                            'rejected' => 'Từ chối'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusBadges[$topic['status']] ?>">
                                            <?= $statusNames[$topic['status']] ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($topic['created_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $topic['topic_id'] ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        <?php if ($topic['status'] === 'pending'): ?>
                                            <a href="/PHP-CN/public/admin/approveTopic/<?= $topic['topic_id'] ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Duyệt đề tài này?')">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                            <a href="/PHP-CN/public/admin/rejectTopic/<?= $topic['topic_id'] ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Từ chối đề tài này?')">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals chi tiết -->
<?php foreach ($data['topics'] as $topic): ?>
<div class="modal fade" id="detailModal<?= $topic['topic_id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết đề tài</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Giảng viên:</strong><br>
                        <?= htmlspecialchars($topic['teacher_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Trạng thái:</strong><br>
                        <?php
                        $statusBadges = [
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger'
                        ];
                        $statusNames = [
                            'pending' => 'Chờ duyệt',
                            'approved' => 'Đã duyệt',
                            'rejected' => 'Từ chối'
                        ];
                        ?>
                        <span class="badge bg-<?= $statusBadges[$topic['status']] ?>">
                            <?= $statusNames[$topic['status']] ?>
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Tên đề tài:</strong><br>
                    <?= htmlspecialchars($topic['title']) ?>
                </div>
                
                <div class="mb-3">
                    <strong>Mô tả:</strong><br>
                    <?= nl2br(htmlspecialchars($topic['description'])) ?>
                </div>
                
                <div class="mb-3">
                    <strong>Yêu cầu:</strong><br>
                    <?= nl2br(htmlspecialchars($topic['requirements'])) ?>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <strong>Số sinh viên tối đa:</strong> <?= $topic['max_students'] ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Đã đăng ký:</strong> <?= $topic['current_students'] ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if ($topic['status'] === 'pending'): ?>
                    <a href="/PHP-CN/public/admin/approveTopic/<?= $topic['topic_id'] ?>" 
                       class="btn btn-success"
                       onclick="return confirm('Duyệt đề tài này?')">
                        <i class="bi bi-check-circle"></i> Duyệt
                    </a>
                    <a href="/PHP-CN/public/admin/rejectTopic/<?= $topic['topic_id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Từ chối đề tài này?')">
                        <i class="bi bi-x-circle"></i> Từ chối
                    </a>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
