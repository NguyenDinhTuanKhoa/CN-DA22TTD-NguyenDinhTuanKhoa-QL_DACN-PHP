/**
 * Chatbot Widget for Admin & Teacher
 * Hỗ trợ tìm kiếm và trả lời câu hỏi từ cơ sở dữ liệu
 */

class ChatBot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.init();
    }

    init() {
        this.createWidget();
        this.bindEvents();
        this.showWelcomeMessage();
    }

    createWidget() {
        const widget = document.createElement('div');
        widget.className = 'chatbot-container';
        widget.innerHTML = `
            <!-- Toggle Button -->
            <button class="chatbot-toggle" id="chatbotToggle">
                <i class="bi bi-robot"></i>
            </button>

            <!-- Chat Window -->
            <div class="chatbot-window" id="chatbotWindow">
                <div class="chatbot-header">
                    <div class="chatbot-header-info">
                        <div class="chatbot-avatar">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div class="chatbot-header-text">
                            <h6>Trợ lý AI</h6>
                            <small><span class="status-dot">●</span> Đang hoạt động</small>
                        </div>
                    </div>
                    <div class="chatbot-header-actions">
                        <button class="chatbot-resize" id="chatbotResize" title="Phóng to/Thu nhỏ">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button>
                        <button class="chatbot-close" id="chatbotClose">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="chatbot-messages" id="chatbotMessages">
                    <!-- Messages will be added here -->
                </div>

                <div class="chatbot-quick-actions" id="quickActions">
                    <button class="quick-action-btn" data-query="gợi ý đề tài về web">💡 Gợi ý đề tài</button>
                    <button class="quick-action-btn" data-query="thống kê tổng quan">📊 Thống kê</button>
                    <button class="quick-action-btn" data-query="đăng ký chờ duyệt">⏳ Chờ duyệt</button>
                    <button class="quick-action-btn" data-query="help">❓ Trợ giúp</button>
                </div>

                <div class="chatbot-input">
                    <input type="text" id="chatbotInput" placeholder="Nhập câu hỏi của bạn..." autocomplete="off">
                    <button id="chatbotSend">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(widget);

        // Cache elements
        this.elements = {
            toggle: document.getElementById('chatbotToggle'),
            window: document.getElementById('chatbotWindow'),
            close: document.getElementById('chatbotClose'),
            resize: document.getElementById('chatbotResize'),
            messages: document.getElementById('chatbotMessages'),
            input: document.getElementById('chatbotInput'),
            send: document.getElementById('chatbotSend'),
            quickActions: document.getElementById('quickActions')
        };
        
        this.isExpanded = false;
    }

    bindEvents() {
        // Toggle chat window
        this.elements.toggle.addEventListener('click', () => this.toggle());
        this.elements.close.addEventListener('click', () => this.close());
        this.elements.resize.addEventListener('click', () => this.toggleSize());

        // Send message
        this.elements.send.addEventListener('click', () => this.sendMessage());
        this.elements.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });

        // Quick actions
        this.elements.quickActions.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const query = btn.dataset.query;
                this.elements.input.value = query;
                this.sendMessage();
            });
        });
    }
    
    toggleSize() {
        this.isExpanded = !this.isExpanded;
        const win = this.elements.window;
        const icon = this.elements.resize.querySelector('i');
        
        if (this.isExpanded) {
            win.style.width = '550px';
            win.style.height = '650px';
            icon.className = 'bi bi-fullscreen-exit';
        } else {
            win.style.width = '420px';
            win.style.height = '550px';
            icon.className = 'bi bi-arrows-fullscreen';
        }
        this.scrollToBottom();
    }

    toggle() {
        this.isOpen = !this.isOpen;
        this.elements.window.classList.toggle('active', this.isOpen);
        if (this.isOpen) {
            this.elements.input.focus();
        }
    }

    close() {
        this.isOpen = false;
        this.elements.window.classList.remove('active');
    }

    showWelcomeMessage() {
        const welcomeMessages = [
            'Xin chào! 👋 Tôi là trợ lý AI của hệ thống quản lý đồ án.',
            'Tôi có thể giúp bạn:\n• 💡 Gợi ý đề tài đồ án theo chủ đề\n• 🔍 Tìm kiếm sinh viên, giảng viên\n• 📊 Xem thống kê hệ thống\n• ⏳ Kiểm tra đăng ký chờ duyệt\n\n📝 Ví dụ: "Gợi ý đề tài về web bán hàng Node.js"'
        ];

        setTimeout(() => {
            this.addMessage(welcomeMessages[0], 'bot');
            setTimeout(() => {
                this.addMessage(welcomeMessages[1], 'bot');
            }, 500);
        }, 300);
    }

    addMessage(text, type, html = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${type}`;
        
        const avatar = type === 'bot' ? 'bi-robot' : 'bi-person-fill';
        const time = new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

        messageDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi ${avatar}"></i>
            </div>
            <div>
                <div class="message-content">${html ? text : this.escapeHtml(text).replace(/\n/g, '<br>')}</div>
                <div class="message-time">${time}</div>
            </div>
        `;

        this.elements.messages.appendChild(messageDiv);
        this.scrollToBottom();
    }

    showTyping() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chatbot-message bot';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="message-avatar">
                <i class="bi bi-robot"></i>
            </div>
            <div class="typing-indicator">
                <span></span>
                <span></span>
                <span></span>
            </div>
        `;
        this.elements.messages.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        const typing = document.getElementById('typingIndicator');
        if (typing) typing.remove();
    }

    scrollToBottom() {
        this.elements.messages.scrollTop = this.elements.messages.scrollHeight;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async sendMessage() {
        const message = this.elements.input.value.trim();
        if (!message || this.isTyping) return;

        // Add user message
        this.addMessage(message, 'user');
        this.elements.input.value = '';

        // Show typing indicator
        this.isTyping = true;
        this.elements.send.disabled = true;
        this.showTyping();

        try {
            // Send to API
            // Detect base URL
            const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
            const baseUrl = isLocalhost ? '/PHP-CN/public' : '/public';
            const response = await fetch(baseUrl + '/api/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            this.hideTyping();
            
            if (data.success) {
                this.addMessage(data.response, 'bot', true);
            } else {
                this.addMessage('Xin lỗi, tôi không thể xử lý yêu cầu của bạn. Vui lòng thử lại!', 'bot');
            }
        } catch (error) {
            console.error('Chatbot error:', error);
            this.hideTyping();
            this.addMessage('Có lỗi kết nối. Vui lòng thử lại sau!', 'bot');
        }

        this.isTyping = false;
        this.elements.send.disabled = false;
    }
}

// Initialize chatbot when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on admin and teacher pages
    const path = window.location.pathname;
    if (path.includes('/admin') || path.includes('/teacher')) {
        new ChatBot();
    }
});
