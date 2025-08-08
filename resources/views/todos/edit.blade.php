@extends('layouts.app')

@section('title', '編輯任務 - ToDo List')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>編輯任務
                </h4>
            </div>
            <div class="card-body p-4">
                <div id="loading-container" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">載入中...</span>
                    </div>
                    <p class="mt-2">載入任務資料中...</p>
                </div>
                
                <form id="edit-todo-form" style="display: none;">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Task Information -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-tasks me-1"></i>任務標題 <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" 
                                       id="title" name="title" required>
                                <div class="invalid-feedback" id="title-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>任務描述
                                </label>
                                <textarea class="form-control" 
                                          id="description" name="description" rows="4"></textarea>
                                <div class="invalid-feedback" id="description-error"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">
                                            <i class="fas fa-tag me-1"></i>分類
                                        </label>
                                        <select class="form-select" id="category_id" name="category_id">
                                            <option value="">選擇分類</option>
                                        </select>
                                        <div class="invalid-feedback" id="category_id-error"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="priority_id" class="form-label">
                                            <i class="fas fa-exclamation-triangle me-1"></i>優先級
                                        </label>
                                        <select class="form-select" id="priority_id" name="priority_id">
                                            <option value="">選擇優先級</option>
                                        </select>
                                        <div class="invalid-feedback" id="priority_id-error"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status_id" class="form-label">
                                            <i class="fas fa-signal me-1"></i>狀態
                                        </label>
                                        <select class="form-select" id="status_id" name="status_id">
                                            <option value="">選擇狀態</option>
                                        </select>
                                        <div class="invalid-feedback" id="status_id-error"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>截止日期
                                        </label>
                                        <input type="datetime-local" class="form-control" 
                                               id="due_date" name="due_date">
                                        <div class="invalid-feedback" id="due_date-error"></div>
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
                                        <select class="form-select" id="assigned_to" name="assigned_to">
                                            <option value="">選擇用戶</option>
                                        </select>
                                        <div class="invalid-feedback" id="assigned_to-error"></div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_completed" 
                                                   name="is_completed" value="1">
                                            <label class="form-check-label" for="is_completed">
                                                <i class="fas fa-check-circle me-1"></i>標記為已完成
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Task Info -->
                            <div class="card bg-info text-white mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-1"></i>任務資訊
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0 small" id="task-info">
                                        <li><i class="fas fa-user me-1"></i>建立者: <span id="creator-name">-</span></li>
                                        <li><i class="fas fa-clock me-1"></i>建立時間: <span id="created-at">-</span></li>
                                        <li><i class="fas fa-edit me-1"></i>最後更新: <span id="updated-at">-</span></li>
                                        <li><i class="fas fa-user-check me-1"></i>目前指派: <span id="current-assignee">-</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('todos.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i>返回列表
                            </a>
                            <a href="#" class="btn btn-outline-info" id="view-details-btn">
                                <i class="fas fa-eye me-1"></i>查看詳情
                            </a>
                        </div>
                        <div>
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo me-1"></i>重置
                            </button>
                            <button type="submit" class="btn btn-warning" id="submit-btn">
                                <i class="fas fa-save me-1"></i>更新任務
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
    const todoId = {{ $todo->id }};
    
    // Load todo data and form options
    loadTodoData();
    loadFormData();

    // Form validation enhancement
    $('#title').on('input', function() {
        if ($(this).val().length > 0) {
            $(this).removeClass('is-invalid').addClass('is-valid');
            $('#title-error').hide();
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Form submission
    $('#edit-todo-form').on('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>更新中...');
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        try {
            // Collect form data
            const formData = new FormData(this);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (key === 'is_completed') {
                    data[key] = value === '1';
                } else if (value) {
                    data[key] = value;
                }
            }
            
            // Update todo via API
            const response = await api.updateTodo(todoId, data);
            
            if (response.success) {
                api.showSuccess('任務更新成功！');
                setTimeout(() => {
                    window.location.href = '{{ route("todos.index") }}';
                }, 1500);
            }
        } catch (error) {
            if (error.type === 'validation') {
                // Show validation errors
                Object.keys(error.errors).forEach(field => {
                    const input = $(`#${field}`);
                    const errorDiv = $(`#${field}-error`);
                    
                    input.addClass('is-invalid');
                    errorDiv.text(error.errors[field][0]).show();
                });
            } else {
                api.showError(error);
            }
        } finally {
            // Re-enable submit button
            submitBtn.prop('disabled', false).html(originalText);
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

    // Confirm before leaving if form has changes
    let formChanged = false;
    $('form input, form textarea, form select').on('input change', function() {
        formChanged = true;
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '您有未儲存的變更，確定要離開嗎？';
        }
    });

    // Reset formChanged when form is submitted
    $('form').on('submit', function() {
        formChanged = false;
    });

    // Load todo data
    async function loadTodoData() {
        try {
            const response = await api.getTodo(todoId);
            
            if (response.success) {
                const todo = response.data;
                
                // Populate form fields
                $('#title').val(todo.title);
                $('#description').val(todo.description);
                
                // Handle due_date timezone conversion
                if (todo.due_date) {
                    // Parse the local time string and format for datetime-local input
                    const dueDate = new Date(todo.due_date);
                    const year = dueDate.getFullYear();
                    const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                    const day = String(dueDate.getDate()).padStart(2, '0');
                    const hours = String(dueDate.getHours()).padStart(2, '0');
                    const minutes = String(dueDate.getMinutes()).padStart(2, '0');
                    const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                    $('#due_date').val(formattedDateTime);
                } else {
                    $('#due_date').val('');
                }
                
                $('#is_completed').prop('checked', todo.is_completed);
                
                // Set select values (will be populated after form data loads)
                window.todoData = todo;
                
                // Update task info
                $('#creator-name').text(todo.creator.name);
                $('#created-at').text(new Date(todo.created_at).toLocaleString());
                $('#updated-at').text(new Date(todo.updated_at).toLocaleString());
                $('#current-assignee').text(todo.assignee ? todo.assignee.name : '未指派');
                
                // Update view details link
                $('#view-details-btn').attr('href', `/todos/${todo.id}`);
                
                // Show form
                $('#loading-container').hide();
                $('#edit-todo-form').show();
            }
        } catch (error) {
            api.showError(error);
            $('#loading-container').html(`
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">載入失敗</h5>
                    <p class="text-muted">無法載入任務資料，請稍後再試</p>
                    <button class="btn btn-primary" onclick="location.reload()">
                        <i class="fas fa-redo me-1"></i>重新載入
                    </button>
                </div>
            `);
        }
    }

    // Load form data (categories, priorities, statuses, users)
    async function loadFormData() {
        try {
            const [categoriesRes, prioritiesRes, statusesRes, usersRes] = await Promise.all([
                api.getCategories(),
                api.getPriorities(),
                api.getStatuses(),
                api.getUsers()
            ]);

            // Populate categories
            const categorySelect = $('#category_id');
            categoriesRes.data.forEach(category => {
                categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
            });

            // Populate priorities
            const prioritySelect = $('#priority_id');
            prioritiesRes.data.forEach(priority => {
                prioritySelect.append(`<option value="${priority.id}">${priority.name}</option>`);
            });

            // Populate statuses
            const statusSelect = $('#status_id');
            statusesRes.data.forEach(status => {
                statusSelect.append(`<option value="${status.id}">${status.name}</option>`);
            });

            // Populate users
            const userSelect = $('#assigned_to');
            usersRes.data.forEach(user => {
                userSelect.append(`<option value="${user.id}">${user.name}</option>`);
            });

            // Set selected values if todo data is loaded
            if (window.todoData) {
                const todo = window.todoData;
                $('#category_id').val(todo.category_id);
                $('#priority_id').val(todo.priority_id);
                $('#status_id').val(todo.status_id);
                $('#assigned_to').val(todo.assigned_to);
            }
        } catch (error) {
            api.showError(error);
        }
    }
});
</script>
@endpush
