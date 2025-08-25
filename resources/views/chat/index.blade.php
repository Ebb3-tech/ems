@extends('layouts.app')

@section('content')
<style>
    /* Modern Color Scheme */
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --primary-dark: #3f37c9;
        --secondary: #f72585;
        --light: #f8f9fa;
        --dark: #212529;
        --gray-100: #f8f9fa;
        --gray-200: #e9ecef;
        --gray-300: #dee2e6;
        --gray-400: #ced4da;
        --gray-500: #adb5bd;
        --gray-600: #6c757d;
        --gray-700: #495057;
        --success: #4cc9f0;
        --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .chat-container {
        display: flex;
        height: 80vh;
        max-height: 700px;
        margin: 20px auto;
        max-width: 1200px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
    }
    
    .sidebar {
        width: 28%;
        background: white;
        border-right: 1px solid var(--gray-200);
        overflow-y: auto;
        transition: all 0.3s ease;
    }
    
    .sidebar-header {
        padding: 20px;
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .chat-area {
        width: 72%;
        display: flex;
        flex-direction: column;
        background: var(--gradient-bg);
        position: relative;
    }
    
    .chat-header {
        padding: 15px 20px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        z-index: 10;
        display: flex;
        align-items: center;
    }
    
    .chat-header h2 {
        font-weight: 600;
        margin: 0;
        color: var(--dark);
        font-size: 1.1rem;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: var(--primary-light);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1IiBoZWlnaHQ9IjUiPgo8cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNmNWY1ZjUiPjwvcmVjdD4KPC9zdmc+');
        scroll-behavior: smooth;
    }
    
    .message {
        margin-bottom: 20px;
        clear: both;
        overflow: hidden;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message-right {
        float: right;
        background: var(--primary);
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        border-radius: 18px 18px 0 18px;
        padding: 12px 18px;
        max-width: 75%;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        position: relative;
        margin-left: 40px;
    }
    
    .message-left {
        float: left;
        background-color: white;
        color: var(--dark);
        border-radius: 18px 18px 18px 0;
        padding: 12px 18px;
        max-width: 75%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        margin-right: 40px;
    }
    
    .message-time {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 5px;
        text-align: right;
    }
    
    .message-left .message-time {
        color: var(--gray-500);
    }
    
    .message-form {
        padding: 15px 20px;
        background-color: white;
        display: flex;
        align-items: center;
        border-top: 1px solid var(--gray-200);
    }
    
    .message-input {
        flex: 1;
        padding: 12px 20px;
        border: 1px solid var(--gray-300);
        border-radius: 24px;
        margin-right: 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        outline: none;
    }
    
    .message-input:focus {
        border-color: var(--primary-light);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
    
    .send-button {
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(67, 97, 238, 0.3);
    }
    
    .send-button:hover {
        background-color: var(--primary-dark);
        transform: scale(1.05);
    }
    
    .send-icon {
        width: 20px;
        height: 20px;
        fill: currentColor;
    }
    
    .user-list {
        padding: 10px 0;
    }
    
    .user-item {
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .user-item:hover {
        background-color: var(--gray-100);
    }
    
    .user-active {
        background-color: rgba(67, 97, 238, 0.08);
        border-left: 3px solid var(--primary);
    }
    
    .user-active .user-name {
        color: var(--primary);
        font-weight: 600;
    }
    
    .user-avatar-sm {
        width: 40px;
        height: 40px;
        background-color: var(--primary-light);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-weight: 500;
    }
    
    .user-active .user-avatar-sm {
        background-color: var(--primary);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        flex: 1;
    }
    
    .user-name {
        color: var(--gray-700);
        font-size: 0.95rem;
    }
    
    .unread-badge {
        background-color: var(--secondary);
        color: white;
        border-radius: 50%;
        min-width: 20px;
        height: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--gray-500);
        padding: 20px;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        color: var(--gray-300);
    }
    
    .empty-state-text {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
    
    .empty-state-subtext {
        font-size: 0.9rem;
    }

    /* Custom scrollbar */
    .messages-container::-webkit-scrollbar, .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    
    .messages-container::-webkit-scrollbar-track, .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-container::-webkit-scrollbar-thumb, .sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 20px;
    }
    
    /* Copyright Footer */
    .copyright {
        text-align: center;
        padding: 15px;
        color: var(--gray-600);
        font-size: 0.85rem;
        background: white;
        border-top: 1px solid var(--gray-200);
        margin-top: 10px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 0 0 12px 12px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>

<div class="chat-container">
    <!-- Sidebar: users list -->
    <div class="sidebar">
        <div class="sidebar-header">
            Messages
        </div>
        <div class="user-list">
            @foreach($users as $userOption)
                <a href="{{ route('chat.index', ['receiver_id' => $userOption->id]) }}" style="text-decoration: none; color: inherit;">
                    <div class="user-item {{ (isset($receiverId) && $receiverId==$userOption->id) ? 'user-active' : '' }}">
                        <div class="user-info">
                            <div class="user-avatar-sm">
                                {{ strtoupper(substr($userOption->name, 0, 1)) }}
                            </div>
                            <div class="user-name">
                                {{ $userOption->name }}
                            </div>
                        </div>
                        
                        @if(isset($unreadCounts[$userOption->id]) && $unreadCounts[$userOption->id] > 0)
                            <div class="unread-badge" id="unread-count-{{ $userOption->id }}">
                                {{ $unreadCounts[$userOption->id] }}
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-area">
        @if(isset($receiverId))
            <div class="chat-header">
                <div class="user-avatar">
                    {{ strtoupper(substr(\App\Models\User::find($receiverId)->name, 0, 1)) }}
                </div>
                <h2>
                    {{ \App\Models\User::find($receiverId)->name }}
                </h2>
            </div>

            <!-- Chat Messages -->
            <div id="chat-box" class="messages-container">
                @foreach($messages as $msg)
                    <div class="message">
                        @if($msg->sender_id == auth()->id())
                            <div class="message-right">
                                {{ $msg->message }}
                                <div class="message-time">
                                    {{ $msg->created_at->format('g:i A') }}
                                </div>
                            </div>
                        @else
                            <div class="message-left">
                                {{ $msg->message }}
                                <div class="message-time">
                                    {{ $msg->created_at->format('g:i A') }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Message Form -->
            <form action="{{ route('chat.send') }}" method="POST" class="message-form">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $receiverId }}">
                <input type="text" name="message" class="message-input" placeholder="Type your message..." required>
                <button type="submit" class="send-button">
                    <svg class="send-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </form>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">💬</div>
                <div class="empty-state-text">Select a conversation</div>
                <div class="empty-state-subtext">Choose a contact from the list to start chatting</div>
            </div>
        @endif
    </div>
</div>

<!-- Copyright Footer -->
<div class="copyright">
    © Ebenezer 2025. All Rights Reserved.
</div>

<script>
    const userId = {{ auth()->id() }};
    const chatBox = document.getElementById('chat-box');
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;

    // Function to update unread message counts
    function updateUnreadCounts() {
        fetch('/chat/unread-counts-per-user', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            Object.keys(data.counts).forEach(senderId => {
                const badge = document.getElementById(`unread-count-${senderId}`);
                const count = data.counts[senderId];
                
                if (count > 0) {
                    if (badge) {
                        badge.textContent = count;
                    } else {
                        const userItem = document.querySelector(`a[href*="receiver_id=${senderId}"] .user-item`);
                        if (userItem) {
                            const unreadBadge = document.createElement('div');
                            unreadBadge.className = 'unread-badge';
                            unreadBadge.id = `unread-count-${senderId}`;
                            unreadBadge.textContent = count;
                            userItem.appendChild(unreadBadge);
                        }
                    }
                } else if (badge) {
                    badge.remove();
                }
            });
        });
    }

    // Check for current receiver ID
    const urlParams = new URLSearchParams(window.location.search);
    const currentReceiverId = urlParams.get('receiver_id');

    window.Echo.private('chat.' + userId)
        .listen('MessageSent', (e) => {
            if(!chatBox) return;
            
            // Add the message to the chat if it's from current conversation
            if(e.chat.sender_id === {{ auth()->id() }} || e.chat.receiver_id === {{ auth()->id() }}) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message';
                
                const messageContent = document.createElement('div');
                const isCurrentUser = e.chat.sender_id === userId;
                
                if(isCurrentUser) {
                    messageContent.className = 'message-right';
                } else {
                    messageContent.className = 'message-left';
                }
                
                // Format time
                const now = new Date();
                const hours = now.getHours() % 12 || 12;
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                const timeFormatted = `${hours}:${minutes} ${ampm}`;
                
                messageContent.innerHTML = `
                    ${e.chat.message}
                    <div class="message-time">${timeFormatted}</div>
                `;
                
                messageDiv.appendChild(messageContent);
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
                
                // If we're currently viewing messages from this sender,
                // mark them as read
                if (currentReceiverId && currentReceiverId == e.chat.sender_id) {
                    markMessagesAsRead(e.chat.sender_id);
                } else {
                    // Otherwise update unread counts
                    updateUnreadCounts();
                }
            }
        });
        
    // Function to mark messages as read
    function markMessagesAsRead(senderId) {
        fetch('/chat/mark-read-from-sender', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sender_id: senderId })
        })
        .then(() => {
            // Update unread counts after marking as read
            updateUnreadCounts();
        });
    }
    
    // Mark messages as read when opening a conversation
    if (currentReceiverId) {
        markMessagesAsRead(currentReceiverId);
    }
    
    // Update unread counts periodically
    setInterval(updateUnreadCounts, 15000);
    
    // Initial update
    document.addEventListener('DOMContentLoaded', function() {
        updateUnreadCounts();
    });
</script>
@endsection