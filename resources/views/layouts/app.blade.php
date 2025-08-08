<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ToDo List')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .todo-item {
            transition: all 0.3s ease;
        }
        .todo-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .priority-badge {
            font-size: 0.75rem;
        }
        .status-badge {
            font-size: 0.75rem;
        }
        .category-badge {
            font-size: 0.75rem;
        }
        .completed {
            text-decoration: line-through;
            opacity: 0.6;
        }
        .overdue {
            border-left: 4px solid #dc3545 !important;
        }
        .due-today {
            border-left: 4px solid #ffc107 !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-tasks me-2"></i>ToDo List
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>首頁
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('todos.index') }}">
                                <i class="fas fa-list me-1"></i>我的任務
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('todos.create') }}">
                                <i class="fas fa-plus me-1"></i>新增任務
                            </a>
                        </li>
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>登入
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>註冊
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i>登出
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-tasks me-2"></i>ToDo List
                    </h5>
                    <p class="text-muted">
                        高效管理您的任務，提升生產力。
                        專為個人和團隊設計的智能任務管理應用程式。
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-muted me-3">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted me-3">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted me-3">
                            <i class="fab fa-linkedin fa-lg"></i>
                        </a>
                        <a href="#" class="text-muted">
                            <i class="fab fa-github fa-lg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">功能特色</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2">
                            <i class="fas fa-check me-2"></i>智能任務分類
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check me-2"></i>團隊協作管理
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check me-2"></i>進度追蹤統計
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check me-2"></i>響應式設計
                        </li>
                    </ul>
                </div>
                
                <div class="col-md-4 mb-4">
                    <h5 class="mb-3">快速連結</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-home me-2"></i>首頁
                            </a>
                        </li>
                        @guest
                            <li class="mb-2">
                                <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-sign-in-alt me-2"></i>登入
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('register') }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-user-plus me-2"></i>註冊
                                </a>
                            </li>
                        @else
                            <li class="mb-2">
                                <a href="{{ route('todos.index') }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-list me-2"></i>我的任務
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="{{ route('todos.create') }}" class="text-muted text-decoration-none">
                                    <i class="fas fa-plus me-2"></i>新增任務
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        <i class="fas fa-code me-1"></i>Built with Laravel 12 & Bootstrap 5
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        &copy; {{ date('Y') }} ToDo List. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <!-- API Utility -->
    <script src="{{ asset('js/api.js') }}"></script>
    
    <script>
        // CSRF token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>
