@extends('layouts.app')

@section('title', 'My Tasks - ToDo List')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                <small>Total Tasks</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tasks fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                                <small>Completed</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                <small>Pending</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['overdue'] }}</h4>
                                <small>Overdue</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('todos.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search tasks...">
                    </div>
                    <div class="col-md-2">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="priority_id" class="form-label">Priority</label>
                        <select class="form-select" id="priority_id" name="priority_id">
                            <option value="">All Priorities</option>
                            @foreach($priorities as $priority)
                                <option value="{{ $priority->id }}" 
                                    {{ request('priority_id') == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status_id" class="form-label">Status</label>
                        <select class="form-select" id="status_id" name="status_id">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" 
                                    {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_completed" class="form-label">Completion</label>
                        <select class="form-select" id="is_completed" name="is_completed">
                            <option value="">All</option>
                            <option value="0" {{ request('is_completed') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('is_completed') === '1' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Todo List -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>My Tasks
                </h5>
                <a href="{{ route('todos.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>New Task
                </a>
            </div>
            <div class="card-body">
                @if($todos->count() > 0)
                    <div class="row">
                        @foreach($todos as $todo)
                            <div class="col-12 mb-3">
                                <div class="card todo-item {{ $todo->is_completed ? 'completed' : '' }} 
                                    {{ $todo->due_date && $todo->due_date->isPast() && !$todo->is_completed ? 'overdue' : '' }}
                                    {{ $todo->due_date && $todo->due_date->isToday() && !$todo->is_completed ? 'due-today' : '' }}">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="card-title mb-1">
                                                    <a href="{{ route('todos.show', $todo) }}" class="text-decoration-none">
                                                        {{ $todo->title }}
                                                    </a>
                                                </h6>
                                                @if($todo->description)
                                                    <p class="card-text text-muted small mb-2">
                                                        {{ Str::limit($todo->description, 100) }}
                                                    </p>
                                                @endif
                                                <div class="d-flex gap-2 flex-wrap">
                                                    @if($todo->category)
                                                        <span class="badge category-badge" style="background-color: {{ $todo->category->color }}">
                                                            <i class="fas fa-tag me-1"></i>{{ $todo->category->name }}
                                                        </span>
                                                    @endif
                                                    @if($todo->priority)
                                                        <span class="badge priority-badge" style="background-color: {{ $todo->priority->color }}">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $todo->priority->name }}
                                                        </span>
                                                    @endif
                                                    @if($todo->status)
                                                        <span class="badge status-badge" style="background-color: {{ $todo->status->color }}">
                                                            <i class="fas fa-signal me-1"></i>{{ $todo->status->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-muted small">
                                                    @if($todo->due_date)
                                                        <div>
                                                            <i class="fas fa-calendar me-1"></i>
                                                            {{ $todo->due_date->format('M d, Y H:i') }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <i class="fas fa-user me-1"></i>
                                                        Created by: {{ $todo->creator->name }}
                                                    </div>
                                                    @if($todo->assignee)
                                                        <div>
                                                            <i class="fas fa-user-check me-1"></i>
                                                            Assigned to: {{ $todo->assignee->name }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-success toggle-complete" 
                                                            data-todo-id="{{ $todo->id }}" 
                                                            data-completed="{{ $todo->is_completed ? '1' : '0' }}">
                                                        <i class="fas {{ $todo->is_completed ? 'fa-undo' : 'fa-check' }}"></i>
                                                    </button>
                                                    <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($todo->created_by === auth()->id())
                                                        <form method="POST" action="{{ route('todos.destroy', $todo) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $todos->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tasks found</h5>
                        <p class="text-muted">Create your first task to get started!</p>
                        <a href="{{ route('todos.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create Task
                        </a>
                    </div>
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
                    const todoItem = button.closest('.todo-item');
                    const icon = button.find('i');
                    
                    if (response.is_completed) {
                        todoItem.addClass('completed');
                        icon.removeClass('fa-check').addClass('fa-undo');
                        button.data('completed', '1');
                    } else {
                        todoItem.removeClass('completed');
                        icon.removeClass('fa-undo').addClass('fa-check');
                        button.data('completed', '0');
                    }
                    
                    // Reload page to update statistics
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
            },
            error: function() {
                alert('Error updating task status');
            }
        });
    });
});
</script>
@endpush
