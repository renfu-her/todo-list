@extends('layouts.app')

@section('title', '新增任務 - ToDo List')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-plus me-2"></i>新增任務
                </h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('todos.store') }}">
                    @csrf
                    
                    <div class="row">
                        <!-- Task Information -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-tasks me-1"></i>任務標題 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>任務描述
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">
                                            <i class="fas fa-tag me-1"></i>分類
                                        </label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id">
                                            <option value="">選擇分類</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="priority_id" class="form-label">
                                            <i class="fas fa-exclamation-triangle me-1"></i>優先級
                                        </label>
                                        <select class="form-select @error('priority_id') is-invalid @enderror" 
                                                id="priority_id" name="priority_id">
                                            <option value="">選擇優先級</option>
                                            @foreach($priorities as $priority)
                                                <option value="{{ $priority->id }}" 
                                                    {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                                                    {{ $priority->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('priority_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_id" class="form-label">
                                            <i class="fas fa-signal me-1"></i>狀態
                                        </label>
                                        <select class="form-select @error('status_id') is-invalid @enderror" 
                                                id="status_id" name="status_id">
                                            <option value="">選擇狀態</option>
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" 
                                                    {{ old('status_id') == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>截止日期
                                        </label>
                                        <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" 
                                               id="due_date" name="due_date" value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Information -->
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-users me-1"></i>任務分配
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="assigned_to" class="form-label">
                                            <i class="fas fa-user-check me-1"></i>指派給
                                        </label>
                                        <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                                id="assigned_to" name="assigned_to">
                                            <option value="">選擇用戶</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" 
                                                    {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_completed" 
                                                   name="is_completed" value="1" {{ old('is_completed') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_completed">
                                                <i class="fas fa-check-circle me-1"></i>標記為已完成
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Tips -->
                            <div class="card bg-info text-white mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-lightbulb me-1"></i>提示
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 small">
                                        <li><i class="fas fa-arrow-right me-1"></i>標題是必填項目</li>
                                        <li><i class="fas fa-arrow-right me-1"></i>設定截止日期有助於時間管理</li>
                                        <li><i class="fas fa-arrow-right me-1"></i>選擇適當的優先級和狀態</li>
                                        <li><i class="fas fa-arrow-right me-1"></i>可以指派給其他團隊成員</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('todos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>返回列表
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo me-1"></i>重置
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>建立任務
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill current date and time for due_date if empty
    if (!$('#due_date').val()) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const dateTimeLocal = `${year}-${month}-${day}T${hours}:${minutes}`;
        $('#due_date').val(dateTimeLocal);
    }

    // Form validation enhancement
    $('#title').on('input', function() {
        if ($(this).val().length > 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Auto-save draft functionality
    let autoSaveTimer;
    $('form input, form textarea, form select').on('input change', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            // Here you could implement auto-save to localStorage or send to server
            console.log('Auto-saving draft...');
        }, 2000);
    });
});
</script>
@endpush
