<?php include_once __DIR__ . '/../../layouts/header.php'; ?>

<style>
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        gap: 15px;
    }
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .chat-header {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        padding: 15px 20px;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
    }
    .message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
    }
    .message.sent {
        flex-direction: row-reverse;
    }
    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
        flex-shrink: 0;
    }
    .message.sent .message-avatar {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        margin-left: 10px;
    }
    .message.received .message-avatar {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        margin-right: 10px;
    }
    .message-content {
        max-width: 70%;
    }
    .message-sender {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 3px;
    }
    .message.sent .message-sender {
        text-align: right;
    }
    .message-bubble {
        padding: 10px 15px;
        border-radius: 15px;
        word-wrap: break-word;
    }
    .message.sent .message-bubble {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
        border-bottom-right-radius: 5px;
    }
    .message.received .message-bubble {
        background: white;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 5px;
    }
    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 3px;
    }
    .message.sent .message-time {
        text-align: right;
    }
    .chat-input-area {
        padding: 15px;
        background: white;
        border-top: 1px solid #e5e7eb;
    }
    .chat-input {
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        padding: 10px 20px;
        resize: none;
    }
    .chat-input:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    .btn-send {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        color: white;
    }
    .btn-send:hover {
        transform: scale(1.05);
        color: white;
    }
    .members-panel {
        width: 280px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .members-header {
        background: #f3f4f6;
        padding: 15px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
    }
    .members-list {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .member-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #f3f4f6;
    }
    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 12px;
    }
    .member-avatar.teacher {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
    .member-avatar.student {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    .member-info {
        flex: 1;
    }
    .member-name {
        font-weight: 500;
        font-size: 14px;
    }
    .member-role {
        font-size: 12px;
        color: #6b7280;
    }
    @media (max-width: 992px) {
        .members-panel { display: none; }
    }
</style>

<div class="d-flex">
    <?php include_once __DIR__ . '/../../layouts/teacher_sidebar.php'; ?>
    
    <div class="main-content flex-grow-1 p-4">
        <div class="chat-wrapper">
            <!-- Chat Main -->
            <div class="chat-main">
                <div class="chat-header">
                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i><?= htmlspecialchars($data['groupInfo']['group_name']) ?></h5>
                    <small><?= count($data['members']) ?> thành viên</small>
                </div>
                
                <div class="chat-messages" id="chatMessages">
                    <?php foreach ($data['messages'] as $msg): ?>
                        <?php $isSent = $msg['user_id'] == $_SESSION['user_id']; ?>
                        <div class="message <?= $isSent ? 'sent' : 'received' ?>">
                            <div class="message-avatar">
                                <?= strtoupper(substr($msg['full_name'], 0, 1)) ?>
                            </div>
                            <div class="message-content">
                                <?php if (!$isSent): ?>
                                    <div class="message-sender"><?= htmlspecialchars($msg['full_name']) ?></div>
                                <?php endif; ?>
                                <div class="message-bubble">
                                    <?= nl2br(htmlspecialchars($msg['message_text'])) ?>
                                </div>
                                <div class="message-time">
                                    <?= date('H:i - d/m', strtotime($msg['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="chat-input-area">
                    <form id="chatForm" class="d-flex align-items-center gap-2">
                        <textarea class="form-control chat-input" id="messageInput" rows="1" placeholder="Nhập tin nhắn..." required></textarea>
                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Members Panel -->
            <div class="members-panel">
                <div class="members-header">
                    <i class="bi bi-people me-2"></i>Thành viên (<?= count($data['members']) ?>)
                </div>
                <div class="members-list">
                    <?php foreach ($data['members'] as $member): ?>
                        <div class="member-item">
                            <div class="member-avatar <?= $member['role'] ?>">
                                <?= strtoupper(substr($member['full_name'], 0, 1)) ?>
                            </div>
                            <div class="member-info">
                                <div class="member-name"><?= htmlspecialchars($member['full_name']) ?></div>
                                <div class="member-role">
                                    <?= $member['role'] === 'teacher' ? 'Giảng viên' : 'Sinh viên - ' . $member['student_code'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const groupId = <?= $data['groupId'] ?>;
const chatMessages = document.getElementById('chatMessages');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const currentUserId = <?= $_SESSION['user_id'] ?>;

function scrollToBottom() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
scrollToBottom();

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const message = messageInput.value.trim();
    if (!message) return;
    
    // Disable button while sending
    const submitBtn = chatForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('<?= BASE_URL ?>/chat/sendMessage', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ group_id: groupId, message: message })
        });
        
        const text = await response.text();
        console.log('Response:', text);
        
        try {
            const result = JSON.parse(text);
            if (result.success) {
                messageInput.value = '';
                loadMessages();
            } else {
                console.error('Send failed:', result.message);
                alert('Lỗi: ' + result.message);
            }
        } catch (parseError) {
            console.error('Parse error:', parseError, text);
        }
    } catch (error) {
        console.error('Fetch error:', error);
    } finally {
        submitBtn.disabled = false;
    }
});

async function loadMessages() {
    try {
        const response = await fetch('<?= BASE_URL ?>/chat/getMessages/' + groupId);
        const result = await response.json();
        if (result.success) {
            renderMessages(result.messages);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderMessages(messages) {
    chatMessages.innerHTML = '';
    messages.forEach(msg => {
        const isSent = msg.user_id == currentUserId;
        const div = document.createElement('div');
        div.className = 'message ' + (isSent ? 'sent' : 'received');
        
        const time = new Date(msg.created_at);
        const timeStr = time.toLocaleString('vi-VN', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit' });
        
        div.innerHTML = `
            <div class="message-avatar">${msg.full_name.charAt(0).toUpperCase()}</div>
            <div class="message-content">
                ${!isSent ? `<div class="message-sender">${msg.full_name}</div>` : ''}
                <div class="message-bubble">${msg.message_text.replace(/\n/g, '<br>')}</div>
                <div class="message-time">${timeStr}</div>
            </div>
        `;
        chatMessages.appendChild(div);
    });
    scrollToBottom();
}

setInterval(loadMessages, 3000);

messageInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        chatForm.dispatchEvent(new Event('submit'));
    }
});
</script>

<?php include_once __DIR__ . '/../../layouts/footer.php'; ?>
