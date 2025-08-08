@extends('layouts.app')

@section('title', 'Home - ToDo List')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-tasks me-3"></i>
                    高效管理您的任務
                </h1>
                <p class="lead mb-4">
                    使用我們的智能 ToDo 應用程式，輕鬆管理您的日常任務、專案和目標。
                    提升工作效率，實現更好的時間管理。
                </p>
                <div class="d-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-user-plus me-2"></i>立即註冊
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>登入
                        </a>
                    @else
                        <a href="{{ route('todos.index') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-list me-2"></i>查看任務
                        </a>
                        <a href="{{ route('todos.create') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-plus me-2"></i>新增任務
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-tasks fa-10x text-light opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold mb-3">為什麼選擇我們的 ToDo 應用程式？</h2>
                <p class="lead text-muted">專為提升您的生產力而設計</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-layer-group fa-3x text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-3">智能分類管理</h4>
                    <p class="text-muted">
                        根據任務類型、優先級和狀態進行智能分類，
                        讓您一目了然地掌握所有任務的進度。
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users fa-3x text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-3">團隊協作</h4>
                    <p class="text-muted">
                        支援任務分配和團隊協作，
                        讓團隊成員能夠共同完成專案目標。
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-3">進度追蹤</h4>
                    <p class="text-muted">
                        即時追蹤任務完成情況，
                        提供詳細的統計數據和進度報告。
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">提升您的生產力</h2>
                <div class="benefit-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="benefit-icon me-3">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">簡化工作流程</h5>
                            <p class="text-muted mb-0">
                                直觀的介面設計，讓您快速上手，無需複雜的學習過程。
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="benefit-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="benefit-icon me-3">
                            <i class="fas fa-clock fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">時間管理</h5>
                            <p class="text-muted mb-0">
                                設定截止日期和提醒，幫助您更好地管理時間和優先級。
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="benefit-item mb-4">
                    <div class="d-flex align-items-start">
                        <div class="benefit-icon me-3">
                            <i class="fas fa-mobile-alt fa-2x text-info"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">隨時隨地存取</h5>
                            <p class="text-muted mb-0">
                                響應式設計，支援各種設備，讓您隨時查看和更新任務。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 text-center">
                <div class="benefits-image">
                    <i class="fas fa-rocket fa-8x text-primary opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bold mb-4">準備開始提升您的生產力了嗎？</h2>
                <p class="lead mb-4">
                    加入數千名已經使用我們 ToDo 應用程式的用戶，
                    開始您的效率提升之旅。
                </p>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>免費註冊
                    </a>
                @else
                    <a href="{{ route('todos.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-tasks me-2"></i>開始管理任務
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .feature-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
        color: #667eea;
    }
    
    .benefit-icon {
        flex-shrink: 0;
    }
    
    .hero-image, .benefits-image {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .opacity-75 {
        opacity: 0.75;
    }
</style>
@endpush
