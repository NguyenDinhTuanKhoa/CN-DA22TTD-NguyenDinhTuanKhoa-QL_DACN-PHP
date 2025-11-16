<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex">
    <?php include_once __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4">Tổng hợp bài nộp của sinh viên</h2>
            
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <p class="mb-0">Tổng số bài nộp: <strong><?= count($data['submissions']) ?></strong></p>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm" onclick="exportToExcel()">
                                <i class="bi bi-file-earmark-excel"></i> Xuất Excel
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="submissionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">STT</th>
                                    <th width="10%">Mã SV</th>
                                    <th width="15%">Họ tên</th>
                                    <th width="20%">Đề tài</th>
                                    <th width="15%">Giảng viên</th>
                                    <th width="15%">GitHub</th>
                                    <th width="15%">Google Drive</th>
                                    <th width="10%">Ngày nộp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['submissions'])): ?>
                                    <?php foreach ($data['submissions'] as $index => $sub): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><strong><?= htmlspecialchars($sub['student_code']) ?></strong></td>
                                            <td><?= htmlspecialchars($sub['student_name']) ?></td>
                                            <td>
                                                <small><?= htmlspecialchars($sub['topic_title']) ?></small>
                                            </td>
                                            <td><small><?= htmlspecialchars($sub['teacher_name']) ?></small></td>
                                            <td>
                                                <?php if (!empty($sub['github_link'])): ?>
                                                    <a href="<?= htmlspecialchars($sub['github_link']) ?>" 
                                                       target="_blank" class="btn btn-sm btn-dark">
                                                        <i class="bi bi-github"></i> Xem
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($sub['google_drive_link'])): ?>
                                                    <a href="<?= htmlspecialchars($sub['google_drive_link']) ?>" 
                                                       target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-google"></i> Xem
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y H:i', strtotime($sub['submitted_at'])) ?></small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Chưa có sinh viên nào nộp bài</td>
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

<script>
function exportToExcel() {
    const table = document.getElementById('submissionsTable');
    let html = table.outerHTML;
    const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'bai_nop_sinh_vien_' + new Date().getTime() + '.xls';
    link.click();
}
</script>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
