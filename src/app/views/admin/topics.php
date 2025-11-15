<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Quản lý đề tài</h2>
            
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
                                        <button class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </button>
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

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
