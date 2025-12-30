<?php include_once __DIR__ . '/../../layouts/header.php'; ?>

<style>
    .chat-list-item {
        border-bottom: 1px solid #e9ecef;
        padding: 15px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .chat-list-item:hover {
        background: #f8f9fa;
    }
    .chat-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    .unread-badge {
        background: #ef4444;
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 12px;
        font-weight: bold;
    }
    .last-message {
        color: #6c757d;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .chat-time {
        color: #6c757d;
        font-size: 12px;
    }
</style>

<div class="d-flex">
    <?php include_once __DIR__ . '/../../layouts/student_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-chat-dots"></i> Tin nhắn với giáo viên hướng dẫn</h2>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <?php if (empty($data['chatGroups'])): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-dots" style="font-size: 64px; color: #dee2e6;"></i>
                            <p class="text-muted mt-3">Chưa có nhóm chat nào</p>
                            <p class="text-muted">Nhóm chat sẽ được tạo tự động khi đăng ký đề tài được duyệt</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['chatGroups'] as $group): ?>
                            <a href="<?= BASE_URL ?>/chat/room/<?= $group['group_id'] ?>" class="text-decoration-none text-dark">
                                <div class="chat-list-item d-flex align-items-center">
                                    <div class="chat-avatar me-3">
                                        <?= strtoupper(substr($group['teacher_name'], 0, 1)) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?= htmlspecialchars($group['teacher_name']) ?></h6>
                                                <p class="mb-0 text-muted small"><?= htmlspecialchars($group['topic_title']) ?></p>
                                            </div>
                                            <?php if ($group['unread_count'] > 0): ?>
                                                <span class="unread-badge"><?= $group['unread_count'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($group['last_message']): ?>
                                            <div class="d-flex justify-content-between mt-2">
                                                <p class="last-message mb-0 flex-grow-1"><?= htmlspecialchars($group['last_message']) ?></p>
                                                <span class="chat-time ms-2"><?= date('H:i', strtotime($group['last_message_time'])) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../layouts/footer.php'; ?>
