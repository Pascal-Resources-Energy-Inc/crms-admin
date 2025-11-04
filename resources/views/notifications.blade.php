@extends('layouts.header')

@section('content')
<div class="notifications-page">
    <div class="notifications-header">
        <div class="header-content">
            <button class="back-btn" onclick="window.location.href='{{ route('home') }}'">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h1>Notifications</h1>
            <button class="mark-all-read-btn">
                <i class="bi bi-check2-all"></i>
                Mark all read
            </button>
        </div>
    </div>

    <!-- Notifications Content -->
    <div class="notifications-content">
        <div class="notifications-list">
            
            @forelse($notifications as $notification)
    @if($notification->status === 'Approved')
        <div class="notification-item {{ $notification->viewed == 0 ? 'unread' : '' }}" data-id="{{ $notification->id }}">
            <div class="notification-avatar payment">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h3>Reward Approved! 🎉</h3>
                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <p class="notification-text">
                    Your {{ $notification->reward ? $notification->reward->description : 'Gcash' }} reward 
                    worth ₱{{ $notification->points_amount }} has been approved! 
                    You can now view the details in your voucher section.
                </p>
                @if($notification->viewed == 0)
                    <div class="notification-status">
                        <span class="unread-indicator"></span>
                    </div>
                @endif
            </div>
        </div>
    
    @elseif($notification->status === 'Submitted')
        <div class="notification-item {{ $notification->viewed == 0 ? 'unread' : '' }}" data-id="{{ $notification->id }}">
            <div class="notification-avatar order">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h3>Reward Claim Submitted</h3>
                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <p class="notification-text">
                    Your claim for {{ $notification->reward ? $notification->reward->description : 'Gcash' }} 
                    worth ₱{{ $notification->points_amount }} has been successfully submitted. 
                    We're reviewing your request.
                </p>
                @if($notification->viewed == 0)
                    <div class="notification-status">
                        <span class="unread-indicator"></span>
                    </div>
                @endif
            </div>
        </div>
    
                    @elseif($notification->status === 'Rejected')
                        <div class="notification-item {{ $notification->viewed == 0 ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                            <div class="notification-avatar" style="background: linear-gradient(135deg, #f44336, #d32f2f);">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h3>Reward Claim Rejected</h3>
                                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notification-text">
                                    Your claim for {{ $notification->reward ? $notification->reward->description : 'Gcash' }} 
                                    worth ₱{{ $notification->points_amount }} has been rejected. 
                                    Please contact support for more information.
                                </p>
                                @if($notification->viewed == 0)
                                    <div class="notification-status">
                                        <span class="unread-indicator"></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    
                    @elseif($notification->status === 'Completed')
                        <div class="notification-item {{ $notification->viewed == 0 ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                            <div class="notification-avatar" style="background: linear-gradient(135deg, #4CAF50, #388E3C);">
                                <i class="bi bi-check-all"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h3>Reward Completed ✅</h3>
                                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notification-text">
                                    Your {{ $notification->reward ? $notification->reward->description : 'Gcash' }} reward 
                                    worth ₱{{ $notification->points_amount }} has been completed and delivered!
                                </p>
                                @if($notification->viewed == 0)
                                    <div class="notification-status">
                                        <span class="unread-indicator"></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    
                    @else
                        {{-- Fallback for any other status --}}
                        <div class="notification-item {{ $notification->viewed == 0 ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                            <div class="notification-avatar" style="background: linear-gradient(135deg, #9E9E9E, #757575);">
                                <i class="bi bi-info-circle-fill"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-header">
                                    <h3>Reward Update</h3>
                                    <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="notification-text">
                                    Status update for {{ $notification->reward ? $notification->reward->description : 'Gcash' }} 
                                    worth ₱{{ $notification->points_amount }}. 
                                    Status: {{ ucfirst($notification->status) }}
                                </p>
                                @if($notification->viewed == 0)
                                    <div class="notification-status">
                                        <span class="unread-indicator"></span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                @empty
                    <div class="notification-item" style="border: none; text-align: center; padding: 40px 20px;">
                        <div style="color: #999;">
                            <i class="bi bi-bell" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                            <h3 style="font-size: 16px; margin-bottom: 8px;">No Notifications Yet</h3>
                            <p style="font-size: 14px;">You'll see updates about your orders and rewards here.</p>
                        </div>
                    </div>
                @endforelse
            
        </div>
    </div>

    <!-- Floating Chat Button -->
    <button class="floating-chat-btn" id="floatingChatBtn">
        <i class="bi bi-chat-dots-fill"></i>
        <span class="chat-badge">Help</span>
    </button>

    <!-- Chatbot Modal -->
    <div class="chatbot-modal" id="chatbotModal">
        <div class="chatbot-container">
            <div class="chatbot-header">
                <div class="bot-info">
                    <div class="bot-avatar">
                        <img src="images/robot.png" alt="robot">
                    </div>
                    <div>
                        <h3>GasLite Assistant</h3>
                        <p class="bot-status">
                            <span class="status-dot"></span>
                            Online - Always here to help
                        </p>
                    </div>
                </div>
                <button class="close-chat-btn" id="closeChatBtn">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">
                    <div class="message-avatar">
                        <img src="images/robot.png" alt="robot">
                    </div>
                    <div class="message-bubble">
                        <p>Hi! 👋 I'm your GasLite Assistant. I'm here to help you with:</p>
                        <ul class="help-list">
                            <li>Order status and tracking</li>
                            <li>Product information and pricing</li>
                            <li>Delivery schedules</li>
                            <li>Payment methods</li>
                            <li>Account settings</li>
                        </ul>
                        <p>How can I assist you today?</p>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="quick-actions" id="quickActions">
                    <button class="quick-action-btn" data-question="What are your LPG cylinder sizes?">
                        <i class="bi bi-fuel-pump"></i>
                        Cylinder Sizes
                    </button>
                    <!-- <button class="quick-action-btn" data-question="How do I track my order?">
                        <i class="bi bi-geo-alt"></i>
                        Track Order
                    </button>
                    <button class="quick-action-btn" data-question="What are the delivery hours?">
                        <i class="bi bi-clock"></i>
                        Delivery Hours
                    </button> -->
                    <button class="quick-action-btn" data-question="What payment methods do you accept?">
                        <i class="bi bi-credit-card"></i>
                        Payment Options
                    </button>
                </div>
            </div>

            <div class="chat-input-container">
                <form id="chatForm" class="chat-input-form">
                    <input 
                        type="text" 
                        id="chatInput" 
                        class="chat-input" 
                        placeholder="Type your question here..."
                        autocomplete="off"
                    >
                    <button type="submit" class="send-btn" id="sendBtn">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('css')
<style>
.notifications-page {
    margin-top: -100px;
    min-height: 100vh;
    background: #f8f9fa;
    padding-bottom: 80px;
}

.notifications-header {
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    padding: 15px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 600px;
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
}

.back-btn:hover {
    background: #f5f5f5;
}

.notifications-header h1 {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.notification-avatar.reward {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.notification-avatar i {
    font-size: 22px;
}

.mark-all-read-btn {
    background: none;
    border: none;
    color: #01B8EA;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 12px;
    border-radius: 8px;
    transition: background 0.2s ease;
    display: flex;
    align-items: center;
    gap: 6px;
}

.mark-all-read-btn:hover {
    background: rgba(1, 184, 234, 0.1);
}

.notifications-content {
    max-width: 600px;
    margin: 25px auto 0;
    padding: 20px;
}

/* Notifications Styles */
.notifications-list {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.notification-item {
    display: flex;
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.notification-item:hover {
    background: #f8f9fa;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background: rgba(1, 184, 234, 0.02);
    border-left: 3px solid #01B8EA;
}

.notification-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    margin-right: 16px;
    flex-shrink: 0;
}

.notification-avatar.order {
    background: linear-gradient(135deg, #4CAF50, #45a049);
}

.notification-avatar.payment {
    background: linear-gradient(135deg, #205CAD, #1a4a8a);
}

.notification-avatar.delivery {
    background: linear-gradient(135deg, #2196F3, #1976D2);
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.notification-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
    line-height: 1.3;
}

.notification-time {
    font-size: 12px;
    color: #999;
    font-weight: 500;
    flex-shrink: 0;
    margin-left: 12px;
}

.notification-text {
    font-size: 14px;
    color: #666;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-status {
    margin-top: 8px;
    display: flex;
    align-items: center;
}

.unread-indicator {
    width: 8px;
    height: 8px;
    background: #01B8EA;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(1, 184, 234, 0.2);
}

/* Floating Chat Button */
.floating-chat-btn {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #01B8EA, #0ea5d9);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(1, 184, 234, 0.4);
    transition: all 0.3s ease;
    z-index: 999;
}

.floating-chat-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 24px rgba(1, 184, 234, 0.5);
}

.floating-chat-btn:active {
    transform: scale(0.95);
}

.chat-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ff4757;
    color: white;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Chatbot Modal */
.chatbot-modal {
    position: fixed;
    bottom: 100px;
    right: 24px;
    width: 400px;
    max-width: calc(100vw - 48px);
    height: 600px;
    max-height: calc(100vh - 150px);
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    opacity: 0;
    transform: translateY(20px) scale(0.95);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.chatbot-modal.active {
    opacity: 1;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

.chatbot-container {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.chatbot-header {
    background: linear-gradient(135deg, #01B8EA, #0ea5d9);
    padding: 20px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bot-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.bot-avatar {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.bot-avatar img{
    width: 52px;
    height: 52px;
}

.bot-info h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.bot-status {
    margin: 4px 0 0;
    font-size: 13px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #4CAF50;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.close-chat-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: background 0.2s ease;
}

.close-chat-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.message {
    display: flex;
    gap: 10px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-avatar {
    width: 36px;
    height: 36px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.message-avatar img {
    width: 35px;
    height: 35px;
}

.user-message {
    flex-direction: row-reverse;
}

.user-message .message-avatar {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.message-bubble {
    max-width: 75%;
    padding: 12px 16px;
    border-radius: 16px;
    background: #f0f0f0;
    color: #333;
    line-height: 1.5;
}

.bot-message .message-bubble {
    border-bottom-left-radius: 4px;
}

.user-message .message-bubble {
    background: linear-gradient(135deg, #01B8EA, #0ea5d9);
    color: white;
    border-bottom-right-radius: 4px;
}

.message-bubble p {
    margin: 0 0 8px 0;
}

.message-bubble p:last-child {
    margin-bottom: 0;
}

.help-list {
    margin: 8px 0;
    padding-left: 20px;
}

.help-list li {
    margin: 4px 0;
    font-size: 14px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 12px;
}

.quick-action-btn {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    color: #333;
}

.quick-action-btn i {
    font-size: 24px;
    color: #01B8EA;
}

.quick-action-btn:hover {
    background: #f8f9fa;
    border-color: #01B8EA;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
}

.typing-dot {
    width: 8px;
    height: 8px;
    background: #999;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-dot:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

.chat-input-container {
    padding: 16px;
    background: #fff;
    border-top: 1px solid #e9ecef;
}

.chat-input-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.chat-input {
    flex: 1;
    border: 1px solid #e0e0e0;
    border-radius: 24px;
    padding: 12px 20px;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
}

.chat-input:focus {
    border-color: #01B8EA;
    box-shadow: 0 0 0 3px rgba(1, 184, 234, 0.1);
}

.send-btn {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #01B8EA, #0ea5d9);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.2s ease;
}

.send-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(1, 184, 234, 0.3);
}

.send-btn:active {
    transform: scale(0.95);
}

/* Suggestion Chips */
.suggestion-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 12px 0;
    padding: 0 46px;
    animation: slideIn 0.3s ease-out;
}

.suggestion-chip {
    background: white;
    border: 1px solid #01B8EA;
    color: #01B8EA;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.suggestion-chip:hover {
    background: #01B8EA;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(1, 184, 234, 0.2);
}

.suggestion-chip:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .notifications-header {
        padding: 12px 15px;
    }
    
    .notifications-content {
        padding: 15px;
    }
    
    .chatbot-modal {
        right: 12px;
        bottom: 90px;
        width: calc(100vw - 24px);
        height: calc(100vh - 150px);
    }
    
    .floating-chat-btn {
        right: 16px;
        bottom: 16px;
        width: 56px;
        height: 56px;
        font-size: 22px;
    }
}

@media (max-width: 480px) {
    .notifications-header h1 {
        font-size: 18px;
    }

    .chatbot-modal {
        right: 12px;
        bottom: 50px;
        width: calc(100vw - 24px);
        height: calc(100vh - 150px);
    }

    .chat-input {
        width: 20px;
        height: 56px;
    }
    
    .mark-all-read-btn {
        font-size: 12px;
        padding: 6px 8px;
    }
    
    .mark-all-read-btn span {
        display: none;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingChatBtn = document.getElementById('floatingChatBtn');
    const chatbotModal = document.getElementById('chatbotModal');
    const closeChatBtn = document.getElementById('closeChatBtn');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');
    
    // Conversation context and history
    let conversationContext = {
        lastTopic: null,
        userPreferences: {},
        mentionedOrders: [],
        conversationHistory: [],
        userName: null
    };
    
    // Enhanced knowledge base with context awareness
    const knowledgeBase = {
        'cylinder sizes': {
            keywords: ['cylinder', 'size', 'sizes', 'lpg', 'tank', 'bottle', 'weight', 'kg', 'gram'],
            priority: ['330g', '11kg', '22kg'],
            response: 'We offer LPG cylinders in the following sizes:\n\n• 330g - Perfect for camping and small outdoor activities\n• 11kg - Standard household size\n• 22kg - For commercial or heavy household use\n\nAll cylinders are safety-tested and certified. Would you like to know the pricing for any specific size?',
            followUp: ['pricing', 'delivery hours', 'safety']
        },
        'track order': {
            keywords: ['track', 'tracking', 'status', 'where', 'order', 'delivery', 'arrive', 'eta', 'location'],
            priority: ['order number', 'tracking', 'status'],
            response: 'To track your order:\n\n1. Go to "My Orders" in your profile\n2. Select the order you want to track\n3. View real-time delivery status\n\nYou can also check your notifications for delivery updates. Would you like me to help you with a specific order number?',
            followUp: ['delivery hours', 'cancel order', 'change address']
        },
        'delivery hours': {
            keywords: ['delivery', 'hours', 'time', 'schedule', 'when', 'open', 'close', 'operating'],
            priority: ['hours', 'schedule', 'time'],
            response: 'Our delivery hours are:\n\n🕐 Monday - Saturday: 8:00 AM - 8:00 PM\n🕐 Sunday: 9:00 AM - 6:00 PM\n\nSame-day delivery is available for orders placed before 3:00 PM. Express delivery (within 2 hours) is available for an additional fee.',
            followUp: ['same day delivery', 'pricing', 'coverage area']
        },
        'payment methods': {
            keywords: ['payment', 'pay', 'methods', 'cash', 'card', 'gcash', 'maya', 'cod', 'online', 'bank'],
            priority: ['gcash', 'cash', 'payment'],
            response: 'We accept the following payment methods:\n\n💰 Cash\n📱 GCash\n\nAll online payments are secured with encryption. Which payment method would you prefer?',
            followUp: ['pricing', 'track order', 'safety']
        },
        'cancel order': {
            keywords: ['cancel', 'cancellation', 'refund', 'stop', 'abort', 'return'],
            priority: ['cancel', 'refund'],
            response: 'To cancel an order:\n\n1. Go to "My Orders"\n2. Select the order you want to cancel\n3. Click "Cancel Order"\n4. Provide a reason\n\nNote: Orders can only be cancelled before the driver is assigned. If the driver is already on the way, please contact customer support at support@GasLite.com or call (02) 8888-9999.',
            followUp: ['refund policy', 'track order', 'contact']
        },
        'same day delivery': {
            keywords: ['same day', 'fast', 'quick', 'urgent', 'rush', 'express', 'asap', 'now', 'immediate'],
            priority: ['express', 'same day', 'urgent'],
            response: 'Yes! We offer same-day delivery options:\n\n⚡ Standard Same-Day: Free for orders placed before 3:00 PM\n⚡ Express Delivery: Within 2 hours (+₱50)\n\nAvailability depends on your location and our current delivery schedule. Express delivery is available in Metro Manila only.',
            followUp: ['delivery hours', 'pricing', 'coverage area']
        },
        'change address': {
            keywords: ['change', 'address', 'location', 'move', 'update', 'different', 'another'],
            priority: ['address', 'location', 'change'],
            response: 'To change your delivery address:\n\n1. Go to your Profile\n2. Select "Addresses"\n3. Edit existing address or add a new one\n\nNote: You can set a default address or choose a different address during checkout. For pending orders, please contact support to update the delivery address.',
            followUp: ['track order', 'contact', 'delivery hours']
        },
        'safety': {
            keywords: ['safe', 'safety', 'secure', 'leak', 'danger', 'risk', 'hazard', 'precaution', 'careful'],
            priority: ['safety', 'leak', 'danger'],
            response: 'Safety is our top priority! 🛡️\n\nAll our LPG cylinders:\n• Are regularly inspected and tested\n• Meet national safety standards\n• Come with safety seals\n• Include usage instructions\n\nSafety tips:\n✓ Store in a well-ventilated area\n✓ Keep away from heat sources\n✓ Check for leaks regularly\n✓ Use proper regulator\n\nIf you smell gas or suspect a leak, turn off the valve immediately and contact us!',
            followUp: ['emergency', 'contact', 'cylinder sizes']
        },
        'pricing': {
            keywords: ['price', 'cost', 'how much', 'rate', 'fee', 'expensive', 'cheap', 'afford', 'budget'],
            priority: ['price', 'cost', 'how much'],
            response: 'Our current pricing:\n\n💵 330g Cylinder: ₱156\n💵 11kg Cylinder: ₱890\n💵 22kg Cylinder: ₱1,650\n\n(Prices include delivery within Metro Manila)\n\nWe also offer:\n• Bulk discounts for multiple cylinders\n• Loyalty rewards program\n• Special promotions (check the app)\n\nDelivery is free for orders over ₱500!',
            followUp: ['cylinder sizes', 'payment methods', 'discount']
        },
        'account': {
            keywords: ['account', 'profile', 'login', 'password', 'register', 'sign', 'forgot', 'reset'],
            priority: ['password', 'login', 'account'],
            response: 'Account-related help:\n\n🔐 Reset Password: Go to Settings > Change Password\n👤 Update Profile: Go to Profile > Edit Information\n📧 Change Email: Contact support for verification\n\nFor security reasons, some changes require email verification. Need help with something specific?',
            followUp: ['contact', 'security', 'privacy']
        },
        'discount': {
            keywords: ['discount', 'promo', 'promotion', 'coupon', 'voucher', 'sale', 'offer', 'deal'],
            priority: ['discount', 'promo', 'coupon'],
            response: 'We have several ways to save! 💰\n\n🎁 Active Promotions:\n• First-time user: 10% off\n• Bulk orders: 5-15% discount\n• Loyalty points: Earn 1 point per ₱10 spent\n\n📱 Check the app for:\n• Weekly flash sales\n• Seasonal promotions\n• Referral bonuses\n\nWould you like to know more about our loyalty program?',
            followUp: ['pricing', 'loyalty', 'bulk order']
        },
        'loyalty': {
            keywords: ['loyalty', 'points', 'rewards', 'member', 'membership', 'earn'],
            priority: ['loyalty', 'points', 'rewards'],
            response: 'Our Loyalty Rewards Program! ⭐\n\n🎯 How it works:\n• Earn 1 point for every ₱10 spent\n• 100 points = ₱50 discount\n• Points never expire\n• Bonus points on special occasions\n\n🏆 Member Tiers:\n• Bronze: 0-500 points\n• Silver: 501-1000 points (5% extra discount)\n• Gold: 1001+ points (10% extra discount)\n\nYour points automatically apply at checkout!',
            followUp: ['pricing', 'discount', 'account']
        },
        'emergency': {
            keywords: ['emergency', 'urgent', 'help', 'gas leak', 'smell', 'fire', 'accident'],
            priority: ['emergency', 'leak', 'fire'],
            response: '⚠️ EMERGENCY PROTOCOL ⚠️\n\nIf you have a gas leak or emergency:\n\n1. 🚫 Turn off gas valve immediately\n2. 🚪 Open windows and doors\n3. 🔥 NO flames, sparks, or electrical switches\n4. 👨‍👩‍👧‍👦 Evacuate the area\n5. 📞 Call us: (02) 8888-9999 (24/7)\n\nFor fire: Call 911 immediately\n\nStay safe and we\'ll assist you right away!',
            followUp: ['safety', 'contact']
        },
        'refund policy': {
            keywords: ['refund', 'money back', 'return', 'warranty', 'guarantee', 'defective'],
            priority: ['refund', 'return', 'warranty'],
            response: 'Our Refund & Return Policy:\n\n✅ Full refund if:\n• Product is damaged/defective\n• Wrong item delivered\n• Cancelled before driver assignment\n\n⏱️ Timeframe:\n• Report issues within 24 hours\n• Refund processed in 3-5 business days\n\n📝 Process:\n1. Contact support with order number\n2. Provide photos (if damaged)\n3. We arrange pickup\n4. Refund issued after verification\n\nNeed to file a refund request?',
            followUp: ['cancel order', 'contact', 'track order']
        },
        'coverage area': {
            keywords: ['area', 'coverage', 'deliver', 'location', 'available', 'service', 'region'],
            priority: ['area', 'coverage', 'deliver'],
            response: 'Our Service Coverage:\n\n📍 Metro Manila: Full coverage\n📍 Nearby provinces: Selected areas\n\nTo check if we deliver to your area:\n1. Enter your address during checkout\n2. System will confirm availability\n3. Or contact us with your location\n\n🚀 Expanding soon to more areas!\n\nWould you like to check a specific location?',
            followUp: ['delivery hours', 'contact', 'pricing']
        },
        'bulk order': {
            keywords: ['bulk', 'wholesale', 'multiple', 'many', 'business', 'commercial', 'company'],
            priority: ['bulk', 'wholesale', 'multiple'],
            response: 'Bulk Order Benefits! 📦\n\n💼 For businesses & large orders:\n• 3-5 cylinders: 5% discount\n• 6-10 cylinders: 10% discount\n• 11+ cylinders: 15% discount + free delivery\n\n🤝 Business Account Perks:\n• Priority delivery\n• Dedicated account manager\n• Monthly invoicing\n• Custom delivery schedules\n\nInterested in setting up a business account?',
            followUp: ['pricing', 'contact', 'payment methods']
        }
    };
    
    // Advanced NLP-like features
    const sentimentKeywords = {
        positive: ['great', 'good', 'excellent', 'perfect', 'love', 'thanks', 'thank you', 'awesome', 'wonderful'],
        negative: ['bad', 'poor', 'terrible', 'awful', 'hate', 'slow', 'disappointed', 'frustrated', 'angry'],
        confused: ['confused', 'don\'t understand', 'what', 'how', 'unclear', 'not sure']
    };
    
    const intentPatterns = {
        question: ['what', 'when', 'where', 'who', 'why', 'how', 'can', 'could', 'would', 'is', 'are', 'do', 'does'],
        complaint: ['problem', 'issue', 'wrong', 'error', 'not working', 'broken', 'bad', 'terrible'],
        request: ['want', 'need', 'looking for', 'help me', 'can you', 'please'],
        feedback: ['think', 'feel', 'opinion', 'feedback', 'review', 'rate']
    };
    
    // Open chatbot modal
    floatingChatBtn.addEventListener('click', function() {
        chatbotModal.classList.add('active');
        chatInput.focus();
    });
    
    // Close chatbot modal
    closeChatBtn.addEventListener('click', function() {
        chatbotModal.classList.remove('active');
    });
    
    // Close modal when clicking outside
    chatbotModal.addEventListener('click', function(e) {
        if (e.target === chatbotModal) {
            chatbotModal.classList.remove('active');
        }
    });
    
    // Quick action buttons
    document.querySelectorAll('.quick-action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const question = this.getAttribute('data-question');
            sendMessage(question);
        });
    });
    
    // Chat form submission
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = chatInput.value.trim();
        if (message) {
            sendMessage(message);
            chatInput.value = '';
        }
    });
    
    function sendMessage(message) {
        // Add user message
        addMessage(message, 'user');
        
        // Store in conversation history
        conversationContext.conversationHistory.push({
            role: 'user',
            message: message,
            timestamp: new Date()
        });
        
        // Hide quick actions after first message
        const quickActions = document.getElementById('quickActions');
        if (quickActions) {
            quickActions.style.display = 'none';
        }
        
        // Show typing indicator
        showTypingIndicator();
        
        // Simulate bot response delay (more realistic)
        const responseDelay = 800 + Math.random() * 1200;
        setTimeout(() => {
            hideTypingIndicator();
            const response = getAdvancedBotResponse(message);
            addMessage(response, 'bot');
            
            // Store bot response in history
            conversationContext.conversationHistory.push({
                role: 'bot',
                message: response,
                timestamp: new Date()
            });
        }, responseDelay);
    }
    
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = sender === 'bot' ? '<img src="images/robot.png" alt="robot">' : '<i class="bi bi-person-fill"></i>';
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble';
        
        // Convert newlines to <br> and handle lists
        const formattedText = text.replace(/\n/g, '<br>');
        bubbleDiv.innerHTML = `<p>${formattedText}</p>`;
        
        messageDiv.appendChild(avatarDiv);
        messageDiv.appendChild(bubbleDiv);
        
        chatMessages.appendChild(messageDiv);
        
        // Add suggestion chips for bot messages
        if (sender === 'bot' && conversationContext.conversationHistory.length > 0) {
            addSuggestionChips();
        }
        
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function addSuggestionChips() {
        // Remove existing chips
        const existingChips = document.querySelector('.suggestion-chips');
        if (existingChips) {
            existingChips.remove();
        }
        
        // Generate contextual suggestions
        const suggestions = getContextualSuggestions();
        if (suggestions.length === 0) return;
        
        const chipsContainer = document.createElement('div');
        chipsContainer.className = 'suggestion-chips';
        
        suggestions.forEach(suggestion => {
            const chip = document.createElement('button');
            chip.className = 'suggestion-chip';
            chip.textContent = suggestion;
            chip.onclick = () => {
                chatInput.value = suggestion;
                chatForm.dispatchEvent(new Event('submit'));
            };
            chipsContainer.appendChild(chip);
        });
        
        chatMessages.appendChild(chipsContainer);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function getContextualSuggestions() {
        const lastTopic = conversationContext.lastTopic;
        const historyLength = conversationContext.conversationHistory.length;
        
        // Different suggestions based on conversation state
        if (historyLength <= 2) {
            return [
                'Show me cylinder prices',
                'How do I track my order?',
                'What are your delivery hours?'
            ];
        }
        
        // Topic-based suggestions
        if (lastTopic && knowledgeBase[lastTopic] && knowledgeBase[lastTopic].followUp) {
            return knowledgeBase[lastTopic].followUp
                .map(topic => {
                    if (topic === 'pricing') return 'What are the prices?';
                    if (topic === 'delivery hours') return 'When can you deliver?';
                    if (topic === 'track order') return 'Track my order';
                    if (topic === 'safety') return 'Safety information';
                    if (topic === 'payment methods') return 'Payment options';
                    if (topic === 'discount') return 'Any discounts available?';
                    if (topic === 'loyalty') return 'Tell me about loyalty rewards';
                    if (topic === 'bulk order') return 'Bulk order discounts';
                    if (topic === 'contact') return 'How to contact support?';
                    return topic;
                })
                .slice(0, 3);
        }
        
        // Default contextual suggestions
        return [
            'Tell me about discounts',
            'Contact support',
            'How to cancel order?'
        ];
    }
    
    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot-message';
        typingDiv.id = 'typingIndicator';
        
        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = '<i class="bi bi-robot"></i>';
        
        const bubbleDiv = document.createElement('div');
        bubbleDiv.className = 'message-bubble typing-indicator';
        bubbleDiv.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
        
        typingDiv.appendChild(avatarDiv);
        typingDiv.appendChild(bubbleDiv);
        
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    function getAdvancedBotResponse(message) {
        const lowerMessage = message.toLowerCase().trim();
        
        // Detect sentiment
        const sentiment = detectSentiment(lowerMessage);
        
        // Extract order numbers if mentioned
        const orderNumbers = extractOrderNumbers(message);
        if (orderNumbers.length > 0) {
            conversationContext.mentionedOrders = orderNumbers;
        }
        
        // Check for context from previous conversation
        const contextualResponse = checkConversationContext(lowerMessage);
        if (contextualResponse) {
            return contextualResponse;
        }
        
        // Enhanced greeting detection with sentiment
        if (lowerMessage.match(/^(hi|hello|hey|good morning|good afternoon|good evening|greetings|yo|sup)/)) {
            const timeOfDay = getTimeOfDay();
            let greeting = `${timeOfDay}! 👋 `;
            
            if (conversationContext.conversationHistory.length > 2) {
                greeting += "Welcome back! ";
            }
            
            return greeting + "I'm your GasLite Assistant. I can help you with:\n\n• 📦 Order tracking & status\n• 🔍 Product information & pricing\n• 🚚 Delivery schedules & options\n• 💳 Payment methods\n• ⚙️ Account management\n\nWhat would you like to know today?";
        }
        
        // Handle follow-up questions (yes/no responses)
        if (lowerMessage.match(/^(yes|yeah|yep|sure|ok|okay|no|nope|nah)$/)) {
            return handleFollowUpResponse(lowerMessage);
        }
        
        // Sentiment-aware responses
        if (sentiment === 'negative') {
            return handleNegativeSentiment(lowerMessage);
        }
        
        // Thank you detection with context
        if (lowerMessage.match(/(thank|thanks|appreciate|helpful|great|perfect)/)) {
            const responses = [
                "You're very welcome! 😊 I'm glad I could help. Is there anything else you'd like to know?",
                "Happy to help! 🌟 Feel free to ask if you have more questions.",
                "My pleasure! 👍 Let me know if you need anything else.",
                "Anytime! 😊 I'm here whenever you need assistance."
            ];
            return responses[Math.floor(Math.random() * responses.length)];
        }
        
        // Check knowledge base with advanced matching
        const matchedTopic = findBestMatch(lowerMessage);
        if (matchedTopic) {
            conversationContext.lastTopic = matchedTopic;
            let response = knowledgeBase[matchedTopic].response;
            
            // Add contextual follow-up suggestions
            if (knowledgeBase[matchedTopic].followUp) {
                response += "\n\n💡 You might also want to know about: " + 
                    knowledgeBase[matchedTopic].followUp.slice(0, 2).join(", ");
            }
            
            return response;
        }
        
        // Order-specific queries
        if (orderNumbers.length > 0) {
            return `I found order number(s): ${orderNumbers.join(', ')} in your message. To track this order:\n\n1. Go to "My Orders" section\n2. Look for order ${orderNumbers[0]}\n3. View real-time status\n\nWould you like help with anything specific about this order?`;
        }
        
        // Contact/support detection
        if (lowerMessage.match(/(contact|support|help|customer service|talk to|speak|agent|human|representative)/)) {
            return "I'm here to help, but if you'd prefer to speak with our support team:\n\n📞 Phone: (02) 8888-9999\n📧 Email: support@GasLite.com\n⏰ Available: 8:00 AM - 8:00 PM (Mon-Sat)\n💬 Response time: Usually within 1 hour\n\nI can still assist you with most questions right now. What would you like help with?";
        }
        
        // Multi-intent detection
        const intents = detectIntents(lowerMessage);
        if (intents.includes('complaint')) {
            return "I'm sorry to hear you're experiencing an issue. 😟 I want to help resolve this for you.\n\nCould you please tell me more about:\n• What exactly happened?\n• Your order number (if applicable)\n• When did this occur?\n\nOr you can reach our support team directly:\n📞 (02) 8888-9999\n📧 support@GasLite.com\n\nWe're committed to making this right!";
        }
        
        // Provide smart suggestions based on message content
        const suggestions = generateSmartSuggestions(lowerMessage);
        if (suggestions.length > 0) {
            return `I'm not entirely sure about that specific question, but based on what you're asking, you might be interested in:\n\n${suggestions.map((s, i) => `${i + 1}. ${s}`).join('\n')}\n\nWhich of these would you like to know more about? Or feel free to rephrase your question!`;
        }
        
        // Enhanced default response with learning capability
        return "I want to make sure I understand correctly. 🤔 Could you please rephrase that or provide more details?\n\nI can help you with:\n✓ Order tracking and delivery status\n✓ Product prices and cylinder sizes\n✓ Payment options and methods\n✓ Delivery schedules and same-day options\n✓ Account settings and security\n✓ Safety information and guidelines\n\nYou can also contact our support team at support@GasLite.com for specialized assistance.";
    }
    
    function detectSentiment(message) {
        for (const [sentiment, keywords] of Object.entries(sentimentKeywords)) {
            if (keywords.some(keyword => message.includes(keyword))) {
                return sentiment;
            }
        }
        return 'neutral';
    }
    
    function detectIntents(message) {
        const detectedIntents = [];
        for (const [intent, keywords] of Object.entries(intentPatterns)) {
            if (keywords.some(keyword => message.includes(keyword))) {
                detectedIntents.push(intent);
            }
        }
        return detectedIntents;
    }
    
    function extractOrderNumbers(message) {
        // Matches patterns like: GZ-12345, #12345, order 12345
        const patterns = [
            /GZ-\d{5}/gi,
            /#\d{5}/g,
            /order\s+#?\d{5}/gi
        ];
        
        const matches = [];
        patterns.forEach(pattern => {
            const found = message.match(pattern);
            if (found) {
                matches.push(...found);
            }
        });
        
        return [...new Set(matches)]; // Remove duplicates
    }
    
    function findBestMatch(message) {
        let bestMatch = null;
        let highestScore = 0;
        
        for (const [topic, data] of Object.entries(knowledgeBase)) {
            let score = 0;
            const words = message.split(' ');
            
            // Check keywords with priority weighting
            data.keywords.forEach(keyword => {
                if (message.includes(keyword)) {
                    score += 1;
                    
                    // Bonus points for priority keywords
                    if (data.priority && data.priority.includes(keyword)) {
                        score += 2;
                    }
                    
                    // Bonus for exact word match
                    if (words.includes(keyword)) {
                        score += 1;
                    }
                }
            });
            
            if (score > highestScore) {
                highestScore = score;
                bestMatch = topic;
            }
        }
        
        return highestScore >= 2 ? bestMatch : null;
    }
    
    function checkConversationContext(message) {
        // Check if user is asking a follow-up question
        if (conversationContext.lastTopic && message.length < 30) {
            const followUpKeywords = ['what about', 'and', 'also', 'tell me more', 'more info', 'details'];
            
            if (followUpKeywords.some(keyword => message.includes(keyword))) {
                const topic = conversationContext.lastTopic;
                if (knowledgeBase[topic] && knowledgeBase[topic].followUp) {
                    return `Regarding ${topic}, here are some related topics:\n\n` +
                        knowledgeBase[topic].followUp.map(f => `• ${f}`).join('\n') +
                        '\n\nWhat would you like to explore?';
                }
            }
        }
        
        // Check if asking about previously mentioned orders
        if (conversationContext.mentionedOrders.length > 0) {
            if (message.match(/\b(it|that|this|the order)\b/i)) {
                return `I see you're referring to order ${conversationContext.mentionedOrders[0]}. You can:\n\n• Track it in "My Orders" section\n• Cancel it (if driver not yet assigned)\n• Change delivery address (contact support)\n• View estimated delivery time\n\nWhat would you like to do?`;
            }
        }
        
        return null;
    }
    
    function handleFollowUpResponse(message) {
        if (message.match(/^(yes|yeah|yep|sure|ok|okay)$/)) {
            if (conversationContext.lastTopic) {
                const topic = conversationContext.lastTopic;
                if (knowledgeBase[topic] && knowledgeBase[topic].followUp) {
                    const followUp = knowledgeBase[topic].followUp[0];
                    conversationContext.lastTopic = followUp;
                    return knowledgeBase[followUp] ? knowledgeBase[followUp].response : 
                        "Let me provide more information about that...";
                }
            }
            return "Great! What specific information would you like to know?";
        } else {
            return "No problem! Is there anything else I can help you with today?";
        }
    }
    
    function handleNegativeSentiment(message) {
        const apologies = [
            "I'm sorry to hear that. 😔 ",
            "I apologize for any inconvenience. ",
            "That's disappointing to hear. "
        ];
        
        const apology = apologies[Math.floor(Math.random() * apologies.length)];
        
        return apology + "I want to help make this right. Could you tell me more about the issue?\n\n" +
            "For urgent matters:\n📞 Call: (02) 8888-9999\n📧 Email: support@GasLite.com\n\n" +
            "I'm here to assist in any way I can.";
    }
    
    function generateSmartSuggestions(message) {
        const suggestions = [];
        const words = message.toLowerCase().split(' ');
        
        // Analyze message and suggest relevant topics
        if (words.some(w => ['when', 'time', 'schedule'].includes(w))) {
            suggestions.push('Delivery hours and schedules');
        }
        if (words.some(w => ['cost', 'price', 'expensive'].includes(w))) {
            suggestions.push('Current pricing and available discounts');
        }
        if (words.some(w => ['find', 'locate', 'where'].includes(w))) {
            suggestions.push('Order tracking and delivery status');
        }
        if (words.some(w => ['safe', 'danger', 'risk'].includes(w))) {
            suggestions.push('Safety guidelines and precautions');
        }
        
        return suggestions.slice(0, 3);
    }
    
    function getTimeOfDay() {
        const hour = new Date().getHours();
        if (hour < 12) return 'Good morning';
        if (hour < 18) return 'Good afternoon';
        return 'Good evening';
    }
    
    const markAllReadBtn = document.querySelector('.mark-all-read-btn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function() {
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bi bi-hourglass-split"></i> Marking...';
            this.disabled = true;
            
            fetch('{{ route("notifications.markAllRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const unreadItems = document.querySelectorAll('.notification-item.unread');
                    
                    unreadItems.forEach(item => {
                        // Remove unread class with animation
                        item.classList.remove('unread');
                        
                        // Find and remove the entire notification-status div
                        const statusDiv = item.querySelector('.notification-status');
                        if (statusDiv) {
                            statusDiv.style.transition = 'opacity 0.3s ease';
                            statusDiv.style.opacity = '0';
                            setTimeout(() => {
                                if (statusDiv.parentNode) {
                                    statusDiv.parentNode.removeChild(statusDiv);
                                }
                            }, 300);
                        }
                    });
                    
                    showToast(`${data.updated_count} notification(s) marked as read`, 'success');
                } else {
                    showToast(data.message || 'Error marking notifications as read', 'error');
                }
            })
            .catch(error => {
                console.error('Error marking all as read:', error);
                showToast('Error marking notifications as read', 'error');
            })
            .finally(() => {
                // Restore button state
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
    
    // Notification items click handler
    const notificationItems = document.querySelectorAll('.notification-item');
    notificationItems.forEach(item => {
        item.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            
            // Mark as viewed via AJAX
            if (itemId) {
                fetch(`{{ url('/notifications') }}/${itemId}/mark-viewed`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Notification marked as viewed');
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as viewed:', error);
                });
            }
            
            // Remove unread indicator
            if (this.classList.contains('unread')) {
                this.classList.remove('unread');
                const indicator = this.querySelector('.unread-indicator');
                if (indicator) {
                    indicator.style.opacity = '0';
                    setTimeout(() => indicator.remove(), 200);
                }
            }
            
            // Redirect to notification details page
            if (itemId) {
                window.location.href = `{{ url('notifications') }}/${itemId}`;
            }
        });
    });
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        let bgColor = '#01B8EA';
        if (type === 'success') bgColor = '#4CAF50';
        if (type === 'error') bgColor = '#f44336';
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(400px);
            transition: transform 0.3s ease;
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
    
    console.log('Advanced AI Chatbot initialized with:');
    console.log('- Natural Language Processing');
    console.log('- Context Awareness & Memory');
    console.log('- Sentiment Analysis');
    console.log('- Multi-intent Detection');
    console.log('- Smart Suggestions');
    console.log('- Conversation History Tracking');
});
</script>
@endsection