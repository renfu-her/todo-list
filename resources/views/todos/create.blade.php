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
                <form id="create-todo-form">
                    @csrf
                    
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
                            <button type="submit" class="btn btn-primary" id="submit-btn">
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
    // Load form data
    loadFormData();

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
            $('#title-error').hide();
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Form submission
    $('#create-todo-form').on('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>建立中...');
        
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
            
            // Create todo via API
            const response = await api.createTodo(data);
            
            if (response.success) {
                api.showSuccess('任務建立成功！');
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
        } catch (error) {
            api.showError(error);
        }
    }
});
</script>
@endpush
