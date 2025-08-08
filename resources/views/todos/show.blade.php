@extends('layouts.app')

@section('title', $todo->title . ' - ToDo List')

@section('content')
<div class="row">
    <!-- Task Details -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>{{ $todo->title }}
                </h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>編輯
                    </a>
                    @if($todo->created_by === auth()->id())
                        <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('確定要刪除這個任務嗎？')">
                                <i class="fas fa-trash me-1"></i>刪除
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Task Status Badges -->
                <div class="mb-3">
                    @if($todo->category)
                        <span class="badge me-2" style="background-color: {{ $todo->category->color }}">
                            <i class="fas fa-tag me-1"></i>{{ $todo->category->name }}
                        </span>
                    @endif
                    @if($todo->priority)
                        <span class="badge me-2" style="background-color: {{ $todo->priority->color }}">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $todo->priority->name }}
                        </span>
                    @endif
                    @if($todo->status)
                        <span class="badge me-2" style="background-color: {{ $todo->status->color }}">
                            <i class="fas fa-signal me-1"></i>{{ $todo->status->name }}
                        </span>
                    @endif
                    @if($todo->is_completed)
                        <span class="badge bg-success me-2">
                            <i class="fas fa-check-circle me-1"></i>已完成
                        </span>
                    @else
                        <span class="badge bg-warning me-2">
                            <i class="fas fa-clock me-1"></i>進行中
                        </span>
                    @endif
                </div>

                <!-- Task Description -->
                @if($todo->description)
                    <div class="mb-4">
                        <h6><i class="fas fa-align-left me-1"></i>任務描述</h6>
                        <p class="text-muted">{{ $todo->description }}</p>
                    </div>
                @endif

                <!-- Task Information Grid -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-user me-1"></i>建立者</h6>
                            <p class="text-muted mb-0">{{ $todo->creator->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-user-check me-1"></i>指派給</h6>
                            <p class="text-muted mb-0">
                                {{ $todo->assignee ? $todo->assignee->name : '未指派' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-calendar me-1"></i>截止日期</h6>
                            <p class="text-muted mb-0">
                                @if($todo->due_date)
                                    {{ $todo->due_date->format('Y-m-d H:i') }}
                                    @if($todo->due_date->isPast() && !$todo->is_completed)
                                        <span class="badge bg-danger ms-2">已逾期</span>
                                    @elseif($todo->due_date->isToday() && !$todo->is_completed)
                                        <span class="badge bg-warning ms-2">今天到期</span>
                                    @endif
                                @else
                                    未設定
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-clock me-1"></i>建立時間</h6>
                            <p class="text-muted mb-0">{{ $todo->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-edit me-1"></i>最後更新</h6>
                            <p class="text-muted mb-0">{{ $todo->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6><i class="fas fa-comments me-1"></i>評論數量</h6>
                            <p class="text-muted mb-0">{{ $todo->comments->count() }} 則評論</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-comments me-2"></i>評論
                </h5>
            </div>
            <div class="card-body">
                <!-- Add Comment Form -->
                <form method="POST" action="{{ route('todos.comments.store', $todo) }}" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="content" class="form-label">新增評論</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="3" placeholder="寫下您的評論..."></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>發表評論
                    </button>
                </form>

                <!-- Comments List -->
                @if($todo->comments->count() > 0)
                    <div class="comments-list">
                        @foreach($todo->comments->sortByDesc('created_at') as $comment)
                            <div class="comment-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <strong class="me-2">{{ $comment->user->name }}</strong>
                                            <small class="text-muted">{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                                        </div>
                                        <p class="mb-0">{{ $comment->content }}</p>
                                    </div>
                                    @if($comment->user_id === auth()->id() || $todo->created_by === auth()->id())
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($comment->user_id === auth()->id())
                                                    <li>
                                                        <a class="dropdown-item" href="#" 
                                                           onclick="editComment({{ $comment->id }})">
                                                            <i class="fas fa-edit me-1"></i>編輯
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('確定要刪除這個評論嗎？')">
                                                            <i class="fas fa-trash me-1"></i>刪除
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">還沒有評論，來發表第一個評論吧！</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-1"></i>快速操作
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success toggle-complete" 
                            data-todo-id="{{ $todo->id }}" 
                            data-completed="{{ $todo->is_completed ? '1' : '0' }}">
                        <i class="fas {{ $todo->is_completed ? 'fa-undo' : 'fa-check' }} me-1"></i>
                        {{ $todo->is_completed ? '標記為未完成' : '標記為已完成' }}
                    </button>
                    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>編輯任務
                    </a>
                    <a href="{{ route('todos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>返回列表
                    </a>
                </div>
            </div>
        </div>

        <!-- Task Statistics -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-1"></i>任務統計
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $todo->comments->count() }}</h4>
                            <small class="text-muted">評論</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-{{ $todo->is_completed ? 'success' : 'warning' }} mb-0">
                            {{ $todo->is_completed ? '100%' : '0%' }}
                        </h4>
                        <small class="text-muted">完成度</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Tasks -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-link me-1"></i>相關任務
                </h6>
            </div>
            <div class="card-body">
                @php
                    $relatedTodos = \App\Models\Todo::where('created_by', $todo->created_by)
                        ->where('id', '!=', $todo->id)
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($relatedTodos->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($relatedTodos as $relatedTodo)
                            <a href="{{ route('todos.show', $relatedTodo) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ Str::limit($relatedTodo->title, 30) }}</h6>
                                    <small class="text-muted">
                                        {{ $relatedTodo->created_at->format('m-d') }}
                                    </small>
                                </div>
                                <small class="text-muted">
                                    @if($relatedTodo->is_completed)
                                        <span class="badge bg-success">已完成</span>
                                    @else
                                        <span class="badge bg-warning">進行中</span>
                                    @endif
                                </small>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">沒有其他相關任務</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle completion status
    $('.toggle-complete').click(function() {
        const button = $(this);
        const todoId = button.data('todo-id');
        const isCompleted = button.data('completed') === '1';
        
        $.ajax({
            url: `/todos/${todoId}/toggle`,
            method: 'PATCH',
            success: function(response) {
                if (response.success) {
                    const icon = button.find('i');
                    const text = button.text().trim();
                    
                    if (response.is_completed) {
                        icon.removeClass('fa-check').addClass('fa-undo');
                        button.text(' 標記為未完成');
                        button.data('completed', '1');
                        button.removeClass('btn-success').addClass('btn-warning');
                    } else {
                        icon.removeClass('fa-undo').addClass('fa-check');
                        button.text(' 標記為已完成');
                        button.data('completed', '0');
                        button.removeClass('btn-warning').addClass('btn-success');
                    }
                    
                    // Update completion badge
                    $('.badge.bg-success, .badge.bg-warning').each(function() {
                        if ($(this).text().includes('已完成') || $(this).text().includes('進行中')) {
                            if (response.is_completed) {
                                $(this).removeClass('bg-warning').addClass('bg-success').html('<i class="fas fa-check-circle me-1"></i>已完成');
                            } else {
                                $(this).removeClass('bg-success').addClass('bg-warning').html('<i class="fas fa-clock me-1"></i>進行中');
                            }
                        }
                    });
                    
                    // Update completion percentage
                    $('.text-success, .text-warning').each(function() {
                        if ($(this).text().includes('%')) {
                            if (response.is_completed) {
                                $(this).removeClass('text-warning').addClass('text-success').text('100%');
                            } else {
                                $(this).removeClass('text-success').addClass('text-warning').text('0%');
                            }
                        }
                    });
                }
            },
            error: function() {
                alert('更新任務狀態時發生錯誤');
            }
        });
    });
});

function editComment(commentId) {
    // Implement comment editing functionality
    alert('編輯評論功能開發中...');
}
</script>
@endpush
