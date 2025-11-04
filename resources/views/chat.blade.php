@extends('layouts.header')

@section('content')
<div class="chat-page">
    <!-- Chat Header -->
    <div class="chat-header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='{{ route('notification') }}'">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div class="user-info">
                <div class="user-avatar">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=01B8EA&color=fff" alt="User">
                    <span class="online-indicator active"></span>
                </div>
                <div class="user-details">
                    <h3>John Doe</h3>
                    <span class="user-status">Active now</span>
                </div>
            </div>
            <div class="header-actions">
                <button class="action-btn" title="More options">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container" id="messagesContainer">
        <div class="messages-wrapper">
            <!-- Example Sent Message -->
            <div class="message-item sent">
                <div class="message-bubble">
                    <p>Hello! This is a sent message.</p>
                    <span class="message-time">9:15 AM <i class="bi bi-check2-all text-primary"></i></span>
                </div>
            </div>

            <!-- Example Received Message -->
            <div class="message-item received">
                <div class="message-avatar">
                    <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=4CAF50&color=fff" alt="Jane">
                </div>
                <div class="message-bubble">
                    <p>Hi there! This is a received message.</p>
                    <span class="message-time">9:16 AM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="message-input-container">
        <form id="messageForm" class="message-form">
            <div class="input-wrapper">
                <!-- Camera Button -->
                <button type="button" class="input-icon" id="cameraBtn" title="Camera">
                    <i class="bi bi-camera"></i>
                </button>

                <!-- Plus Button -->
                <button type="button" class="input-icon" id="plusBtn" title="Add">
                    <i class="bi bi-plus-circle"></i>
                </button>

                <!-- Textarea -->
                <textarea 
                    id="messageInput" 
                    placeholder="Type a message..." 
                    rows="1"
                    maxlength="1000"
                ></textarea>

                <!-- Send Button -->
                <button type="button" class="send-btn" id="sendBtn">
                    <i class="bi bi-send-fill"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<style>
.chat-page {
    margin-top: -100px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
}

/* Header */
.chat-header {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    padding: 12px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 800px;
    margin: 0 auto;
}

.back-btn {
    background: none;
    border: none;
    color: #333;
    font-size: 20px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

.back-btn:hover {
    background: #f5f5f5;
}

.user-info {
    display: flex;
    align-items: center;
    flex: 1;
    min-width: 0;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 12px;
    position: relative;
    flex-shrink: 0;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    background: #4CAF50;
    border: 2px solid white;
    border-radius: 50%;
    display: none;
}

.online-indicator.active {
    display: block;
}

.user-details h3 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-status {
    font-size: 12px;
    color: #999;
}

.header-actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    color: #666;
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: background 0.2s ease;
}

.action-btn:hover {
    background: #f5f5f5;
}

/* Messages */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background: #f8f9fa;
}

.messages-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.message-item {
    display: flex;
    margin-bottom: 16px;
    animation: messageSlide 0.3s ease-out;
}

@keyframes messageSlide {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-item.sent { justify-content: flex-end; }
.message-item.received { justify-content: flex-start; }

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 8px;
    flex-shrink: 0;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 18px;
    word-wrap: break-word;
}

.message-item.sent .message-bubble {
    background: #01B8EA;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-item.received .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message-time {
    display: block;
    font-size: 11px;
    margin-top: 4px;
    opacity: 0.7;
}

.message-item.sent .message-time { text-align: right; }

/* Message Input */
.message-input-container {
    background: #fff;
    border-top: 1px solid #e9ecef;
    padding: 12px 20px;
    position: sticky;
    bottom: 0;
    z-index: 100;
}

.message-form {
    max-width: 800px;
    margin: 0 auto;
}

.input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8f9fa;
    border-radius: 24px;
    padding: 8px 12px;
}

#messageInput {
    flex: 1;
    border: none;
    background: transparent;
    resize: none;
    font-size: 14px;
    padding: 6px 8px;
    max-height: 120px;
    overflow-y: auto;
}

#messageInput:focus { outline: none; }

.input-icon {
    background: none;
    border: none;
    font-size: 18px;
    color: #555;
    cursor: pointer;
    padding: 6px;
    border-radius: 50%;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.input-icon:hover { background: #e9ecef; }

.send-btn {
    background: #01B8EA;
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.send-btn:hover { background: #0ea5d9; transform: scale(1.05); }
.send-btn:disabled { background: #ccc; cursor: not-allowed; }

/* Scrollbar */
.messages-container::-webkit-scrollbar { width: 6px; }
.messages-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
.messages-container::-webkit-scrollbar-thumb:hover { background: #999; }

/* Responsive */
@media (max-width: 768px) {
    .message-bubble { max-width: 80%; }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendBtn.click();
        }
    });

    sendBtn.addEventListener('click', function() {
        const text = messageInput.value.trim();
        if (!text) return;

        const wrapper = document.querySelector('.messages-wrapper');
        const div = document.createElement('div');
        div.className = 'message-item sent';
        div.innerHTML = `
            <div class="message-bubble">
                <p>${text}</p>
                <span class="message-time">Now <i class="bi bi-check2-all text-muted"></i></span>
            </div>
        `;
        wrapper.appendChild(div);
        messageInput.value = '';
        messageInput.style.height = 'auto';
        wrapper.scrollTop = wrapper.scrollHeight;
    });

    document.getElementById('cameraBtn').addEventListener('click', () => {
        alert('Camera button clicked!');
    });

    document.getElementById('plusBtn').addEventListener('click', () => {
        alert('Plus button clicked!');
    });
});
</script>
@endsection
