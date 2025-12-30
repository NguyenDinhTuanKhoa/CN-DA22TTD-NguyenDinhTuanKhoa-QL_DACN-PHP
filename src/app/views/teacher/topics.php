<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý đề tài</h2>
                <a href="/PHP-CN/public/teacher/createTopic" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tạo đề tài mới
                </a>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill"></i> Bạn có thể tạo tối đa <strong>10 đề tài</strong>. 
                Hiện tại: <strong><?= count($data['topics']) ?>/10</strong>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên đề tài</th>
                                    <th>Mô tả</th>
                                    <th>Số SV tối đa</th>
                                    <th>Đã đăng ký</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['topics'])): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Chưa có đề tài nào. Hãy tạo đề tài mới!
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['topics'] as $index => $topic): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($topic['title']) ?></strong></td>
                                        <td><?= htmlspecialchars(substr($topic['description'], 0, 50)) ?>...</td>
                                        <td><?= $topic['max_students'] ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= $topic['current_students'] ?></span>
                                        </td>
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
                                        <td>
                                            <a href="/PHP-CN/public/teacher/editTopic/<?= $topic['topic_id'] ?>" 
                                               class="btn btn-sm btn-warning" title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="/PHP-CN/public/teacher/deleteTopic/<?= $topic['topic_id'] ?>" 
                                               class="btn btn-sm btn-danger" title="Xóa"
                                               onclick="return confirm('Bạn có chắc muốn xóa đề tài này?\n\nLưu ý: Các sinh viên đã đăng ký sẽ bị ảnh hưởng!')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>