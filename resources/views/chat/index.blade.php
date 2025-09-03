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
        flex-direction: column;
        height: calc(100vh - 140px);
        max-height: 700px;
        margin: 20px auto;
        max-width: 1200px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        position: relative;
    }
    
    @media (min-width: 768px) {
        .chat-container {
            flex-direction: row;
        }
    }
    
    .sidebar {
        width: 100%;
        height: 100%;
        background: white;
        overflow-y: auto;
        transition: all 0.3s ease;
        display: none;
        position: absolute;
        z-index: 20;
        top: 0;
        left: 0;
    }
    
    .sidebar.active {
        display: block;
    }
    
    @media (min-width: 768px) {
        .sidebar {
            width: 280px;
            display: block;
            position: relative;
            border-right: 1px solid var(--gray-200);
        }
    }
    
    @media (min-width: 992px) {
        .sidebar {
            width: 320px;
        }
    }
    
    .sidebar-header {
        padding: 15px;
        background-color: var(--primary);
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    @media (min-width: 768px) {
        .sidebar-header {
            padding: 20px;
            font-size: 1.2rem;
        }
    }
    
    .sidebar-close {
        display: block;
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
    }
    
    @media (min-width: 768px) {
        .sidebar-close {
            display: none;
        }
    }
    
    .chat-area {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: var(--gradient-bg);
        position: relative;
    }
    
    .chat-header {
        padding: 12px 15px;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    @media (min-width: 768px) {
        .chat-header {
            padding: 15px 20px;
        }
    }
    
    .chat-header-left {
        display: flex;
        align-items: center;
    }
    
    .chat-header h2 {
        font-weight: 600;
        margin: 0;
        color: var(--dark);
        font-size: 1rem;
    }
    
    @media (min-width: 768px) {
        .chat-header h2 {
            font-size: 1.1rem;
        }
    }
    
    .menu-toggle {
        background: none;
        border: none;
        color: var(--dark);
        margin-right: 10px;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (min-width: 768px) {
        .menu-toggle {
            display: none;
        }
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        background-color: var(--primary-light);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-weight: 600;
        font-size: 1rem;
    }
    
    @media (min-width: 768px) {
        .user-avatar {
            width: 40px;
            height: 40px;
            margin-right: 15px;
            font-size: 1.1rem;
        }
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1IiBoZWlnaHQ9IjUiPgo8cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNmNWY1ZjUiPjwvcmVjdD4KPC9zdmc+');
        scroll-behavior: smooth;
    }
    
    @media (min-width: 768px) {
        .messages-container {
            padding: 20px;
        }
    }
    
    .message {
        margin-bottom: 16px;
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
        padding: 10px 14px;
        max-width: 85%;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        position: relative;
        margin-left: 10px;
        word-wrap: break-word;
    }
    
    @media (min-width: 576px) {
        .message-right {
            max-width: 75%;
            padding: 12px 18px;
            margin-left: 40px;
        }
    }
    
    .message-left {
        float: left;
        background-color: white;
        color: var(--dark);
        border-radius: 18px 18px 18px 0;
        padding: 10px 14px;
        max-width: 85%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        margin-right: 10px;
        word-wrap: break-word;
    }
    
    @media (min-width: 576px) {
        .message-left {
            max-width: 75%;
            padding: 12px 18px;
            margin-right: 40px;
        }
    }
    
    .message-time {
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 4px;
        text-align: right;
    }
    
    @media (min-width: 576px) {
        .message-time {
            font-size: 0.7rem;
            margin-top: 5px;
        }
    }
    
    .message-left .message-time {
        color: var(--gray-500);
    }
    
    .message-form {
        padding: 10px 15px;
        background-color: white;
        display: flex;
        align-items: center;
        border-top: 1px solid var(--gray-200);
    }
    
    @media (min-width: 768px) {
        .message-form {
            padding: 15px 20px;
        }
    }
    
    .message-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid var(--gray-300);
        border-radius: 24px;
        margin-right: 8px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        outline: none;
    }
    
    @media (min-width: 768px) {
        .message-input {
            padding: 12px 20px;
            margin-right: 10px;
        }
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
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(67, 97, 238, 0.3);
        flex-shrink: 0;
    }
    
    @media (min-width: 768px) {
        .send-button {
            width: 46px;
            height: 46px;
        }
    }
    
    .send-button:hover {
        background-color: var(--primary-dark);
        transform: scale(1.05);
    }
    
    .send-icon {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }
    
    @media (min-width: 768px) {
        .send-icon {
            width: 20px;
            height: 20px;
        }
    }
    
    .user-list {
        padding: 8px 0;
    }
    
    @media (min-width: 768px) {
        .user-list {
            padding: 10px 0;
        }
    }
    
    .user-item {
        padding: 10px 15px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    @media (min-width: 768px) {
        .user-item {
            padding: 12px 20px;
        }
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
        width: 36px;
        height: 36px;
        background-color: var(--primary-light);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-weight: 500;
        flex-shrink: 0;
    }
    
    @media (min-width: 768px) {
        .user-avatar-sm {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }
    }
    
    .user-active .user-avatar-sm {
        background-color: var(--primary);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }
    
    .user-name {
        color: var(--gray-700);
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        flex-shrink: 0;
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
        font-size: 3rem;
        margin-bottom: 15px;
        color: var(--gray-300);
    }
    
    @media (min-width: 768px) {
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
    }
    
    .empty-state-text {
        font-size: 1rem;
        margin-bottom: 8px;
    }
    
    @media (min-width: 768px) {
        .empty-state-text {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
    }
    
    .empty-state-subtext {
        font-size: 0.85rem;
    }
    
    @media (min-width: 768px) {
        .empty-state-subtext {
            font-size: 0.9rem;
        }
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
        padding: 12px;
        color: var(--gray-600);
        font-size: 0.8rem;
        background: white;
        border-top: 1px solid var(--gray-200);
        margin-top: 10px;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 0 0 12px 12px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    @media (min-width: 768px) {
        .copyright {
            padding: 15px;
            font-size: 0.85rem;
        }
    }
    
    /* Dark overlay for mobile sidebar */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 19;
        display: none;
    }
    
    .sidebar-overlay.active {
        display: block;
    }
</style>

<!-- Sidebar overlay for mobile -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="chat-container">
    <!-- Sidebar: users list -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span>Messages</span>
            <button class="sidebar-close" id="sidebar-close">×</button>
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
                <div class="chat-header-left">
                    <button class="menu-toggle" id="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <div class="user-avatar">
                        {{ strtoupper(substr(\App\Models\User::find($receiverId)->name, 0, 1)) }}
                    </div>
                    <h2>
                        {{ \App\Models\User::find($receiverId)->name }}
                    </h2>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chat-box" class="messages-container">
                @foreach($messages as $msg)
    <div class="message">
    @if($msg->sender_id == auth()->id())
        <div class="message-right">
            @if($msg->message)
                <p>{{ $msg->message }}</p>
            @endif

            @if($msg->file_type && $msg->file_path)
                @if($msg->file_type == 'image')
                    <img src="{{ asset('storage/' . $msg->file_path) }}" style="max-width:200px; border-radius:10px;">
                @elseif($msg->file_type == 'audio')
                    <audio controls>
                        <source src="{{ asset('storage/' . $msg->file_path) }}" type="audio/webm">
                        Your browser does not support the audio element.
                    </audio>
                @elseif($msg->file_type == 'pdf')
                    <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank">View PDF</a>
                @endif
            @endif

            <div class="message-time">{{ $msg->created_at->format('g:i A') }}</div>
        </div>
    @else
        <div class="message-left">
            @if($msg->message)
                <p>{{ $msg->message }}</p>
            @endif

            @if($msg->file_type && $msg->file_path)
                @if($msg->file_type == 'image')
                    <img src="{{ asset('storage/' . $msg->file_path) }}" style="max-width:200px; border-radius:10px;">
                @elseif($msg->file_type == 'audio')
                    <audio controls>
                        <source src="{{ asset('storage/' . $msg->file_path) }}" type="audio/webm">
                        Your browser does not support the audio element.
                    </audio>
                @elseif($msg->file_type == 'pdf')
                    <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" style="color: red;">>View PDF</a>
                @endif
            @endif

            <div class="message-time">{{ $msg->created_at->format('g:i A') }}</div>
        </div>
    @endif
</div>

@endforeach

            </div>

            <!-- Message Form -->
            <form id="chat-form" action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="receiver_id" value="{{ $receiverId }}">

    <div class="mb-2 d-flex align-items-center gap-1">
        <input name="message" class="form-control" rows="1" placeholder="Type your message...">

        <!-- File upload button -->
        <button type="button" id="file-btn" class="btn btn-light">📎</button>
        <input type="file" id="file-input" name="file" hidden>

        <!-- Mic / record button -->
        <button type="button" id="record-btn" class="btn btn-light">🎤</button>

        <!-- Send -->
        <button type="submit" class="btn btn-primary">Send</button>
    </div>
</form>



        @else
            <div class="chat-header">
                <div class="chat-header-left">
                    <button class="menu-toggle" id="menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h2>Conversations</h2>
                </div>
            </div>
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
let mediaRecorder;
let audioChunks = [];
let isRecording = false;

const recordBtn = document.getElementById('record-btn');
const fileBtn = document.getElementById('file-btn');
const fileInput = document.getElementById('file-input');
const chatForm = document.getElementById('chat-form');

// 📎 Open file selector when button clicked
fileBtn.addEventListener('click', () => {
    fileInput.click();
});

// 🎤 Recording logic
recordBtn.addEventListener('click', async () => {
    if (!isRecording) {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();
            audioChunks = [];

            mediaRecorder.addEventListener("dataavailable", event => {
                audioChunks.push(event.data);
            });

            mediaRecorder.addEventListener("stop", () => {
                const audioBlob = new Blob(audioChunks, { type: "audio/webm; codecs=opus" });
                const file = new File([audioBlob], "voice_note.webm", { type: "audio/webm" });

                const formData = new FormData(chatForm);
                formData.append("file", file);

                fetch(chatForm.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(() => {
                    window.location.reload(); // reload to show new message
                });
            });

            recordBtn.textContent = "⏹"; // Change to stop
            isRecording = true;
        } catch (err) {
            alert("Microphone access denied!");
        }
    } else {
        mediaRecorder.stop();
        recordBtn.textContent = "🎤";
        isRecording = false;
    }
});
</script>



<script>
    const userId = {{ auth()->id() }};
    const chatBox = document.getElementById('chat-box');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const menuToggle = document.getElementById('menu-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    
    // Scroll chat to bottom
    if(chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    
    // Mobile sidebar toggle
    if(menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    }
    
    if(sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Re-enable scrolling
        });
    }
    
    if(sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Re-enable scrolling
        });
    }
    
    // Responsive handling - close sidebar when window resizes to desktop
    window.addEventListener('resize', function() {
        if(window.innerWidth >= 768) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

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

        // Only add messages for current conversation
        if(e.chat.sender_id === userId || e.chat.receiver_id === userId) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message';

            const messageContent = document.createElement('div');
            const isCurrentUser = e.chat.sender_id === userId;

            messageContent.className = isCurrentUser ? 'message-right' : 'message-left';

            // Format message content
            let contentHTML = '';

            // Add text message
            if(e.chat.message) {
                contentHTML += `<p>${e.chat.message}</p>`;
            }

            // Add file if exists
            if(e.chat.file_type) {
                if(e.chat.file_type === 'image') {
                    contentHTML += `<img src="${e.chat.file_url}" style="max-width:200px; border-radius:10px;">`;
                } else if(e.chat.file_type === 'audio') {
                    contentHTML += `<audio controls>
                        <source src="${e.chat.file_url}" type="audio/webm">
                        Your browser does not support the audio element.
                    </audio>`;
                } else {
                    // For PDFs or other files
                    contentHTML += `<a href="${e.chat.file_url}" target="_blank">Download ${e.chat.file_type}</a>`;
                }
            }

            // Add message time
            const msgDate = new Date(e.chat.created_at);
            const hours = msgDate.getHours() % 12 || 12;
            const minutes = msgDate.getMinutes().toString().padStart(2, '0');
            const ampm = msgDate.getHours() >= 12 ? 'PM' : 'AM';
            const timeFormatted = `${hours}:${minutes} ${ampm}`;

            contentHTML += `<div class="message-time">${timeFormatted}</div>`;

            messageContent.innerHTML = contentHTML;
            messageDiv.appendChild(messageContent);

            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;

            // Mark as read if current conversation
            if (currentReceiverId && currentReceiverId == e.chat.sender_id) {
                markMessagesAsRead(e.chat.sender_id);
            } else {
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