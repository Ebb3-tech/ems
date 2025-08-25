<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.ebb', 'Employee Management') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        .message-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            padding: 3px 6px;
            border-radius: 50%;
            background: #ff3366;
            color: white;
            font-size: 0.7rem;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }
        
        .chat-icon-container {
            position: relative;
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <marquee><a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.ebb', 'Employee Management System') }}
            </a></marquee>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <!-- Home -->
                    <li class="nav-item me-2">
                        <a href="{{ route('home') }}" class="nav-link">
                            Home
                        </a>
                    </li>

                    <!-- Chat with notification badge -->
                    <li class="nav-item me-2">
                        <a href="{{ route('chat.index') }}" class="nav-link d-flex align-items-center">
                            <div class="chat-icon-container">
                                <i class="bi bi-chat-dots-fill"></i>
                                <span class="message-badge" id="unread-count" style="display: none;">0</span>
                            </div>
                            Chat
                        </a>
                    </li>
                    @endauth

                    @auth
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit-password') }}">
                                        Change Password
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item me-2">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @auth
    <!-- Unread Messages Script -->
    <script>
        // Function to fetch and update unread count
        function fetchUnreadCount() {
            // Use a random parameter to prevent caching
            const timestamp = new Date().getTime();
            
            fetch('/chat/unread-count?t=' + timestamp, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const badgeElement = document.getElementById('unread-count');
                if (data.count > 0) {
                    badgeElement.textContent = data.count;
                    badgeElement.style.display = 'block';
                } else {
                    badgeElement.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching unread count:', error);
            });
        }

        // Update count when page loads
        document.addEventListener('DOMContentLoaded', function() {
            fetchUnreadCount();
            
            // Mark messages as read when on chat page
            if (window.location.pathname.includes('/chat')) {
                fetch('/chat/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(() => {
                    fetchUnreadCount();
                })
                .catch(error => {
                    console.error('Error marking messages as read:', error);
                });
            }
        });

        // Update count every 15 seconds
        setInterval(fetchUnreadCount, 15000);
    </script>
    @endauth
</body>
</html>