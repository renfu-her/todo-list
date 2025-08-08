@extends('layouts.app')

@section('title', '我的任務 - ToDo List')

@section('content')
<div class="row">
    <!-- Current Date Time Display -->
    <div class="col-12 mb-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span id="current-datetime">載入中...</span>
                </h5>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="col-12 mb-4">
        <div class="row" id="stats-container">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0" id="total-tasks">-</h4>
                                <small>總任務數</small>
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
                                <h4 class="mb-0" id="completed-tasks">-</h4>
                                <small>已完成</small>
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
                                <h4 class="mb-0" id="pending-tasks">-</h4>
                                <small>進行中</small>
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
                                <h4 class="mb-0" id="overdue-tasks">-</h4>
                                <small>已逾期</small>
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
                <form id="filter-form" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">搜尋</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="搜尋任務...">
                    </div>
                    <div class="col-md-2">
                        <label for="category_id" class="form-label">分類</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">所有分類</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="priority_id" class="form-label">優先級</label>
                        <select class="form-select" id="priority_id" name="priority_id">
                            <option value="">所有優先級</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status_id" class="form-label">狀態</label>
                        <select class="form-select" id="status_id" name="status_id">
                            <option value="">所有狀態</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="is_completed" class="form-label">完成狀態</label>
                        <select class="form-select" id="is_completed" name="is_completed">
                            <option value="">全部</option>
                            <option value="0">進行中</option>
                            <option value="1">已完成</option>
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
                    <i class="fas fa-list me-2"></i>我的任務
                </h5>
                <a href="{{ route('todos.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>新增任務
                </a>
            </div>
            <div class="card-body">
                <div id="todos-container">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">載入中...</span>
                        </div>
                        <p class="mt-2">載入任務中...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Todo Item Template -->
<template id="todo-item-template">
    <div class="col-12 mb-3">
        <div class="card todo-item">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h6 class="card-title mb-1">
                            <a href="#" class="text-decoration-none todo-title"></a>
                        </h6>
                        <p class="card-text text-muted small mb-2 todo-description"></p>
                        <div class="d-flex gap-2 flex-wrap todo-badges"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small todo-info"></div>
                    </div>
                    <div class="col-md-3 text-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-success toggle-complete">
                                <i class="fas fa-check"></i>
                            </button>
                            <a href="#" class="btn btn-sm btn-outline-primary edit-todo">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-todo">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentFilters = {};

    // Check if user is authenticated
    if (!api.token) {
        console.log('No token found, redirecting to login');
        window.location.href = '/login';
        return;
    }

    console.log('Token found:', api.token ? 'Yes' : 'No');

    // Load initial data
    updateCurrentDateTime();
    loadStats();
    loadFilters();
    loadTodos();
    
    // Update current date time every second
    setInterval(updateCurrentDateTime, 1000);

    // Update current date time function
    function updateCurrentDateTime() {
        const now = new Date();
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Taipei'
        };
        
        const formattedDateTime = now.toLocaleString('zh-TW', options);
        $('#current-datetime').text(formattedDateTime);
    }

    // Filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        currentFilters = {};
        
        // Collect filter values
        const formData = new FormData(this);
        for (let [key, value] of formData.entries()) {
            if (value) {
                currentFilters[key] = value;
            }
        }
        
        currentPage = 1;
        loadTodos();
    });

    // Load statistics
    async function loadStats() {
        try {
            const response = await api.getTodoStats();
            if (response.success) {
                const stats = response.data;
                $('#total-tasks').text(stats.total);
                $('#completed-tasks').text(stats.completed);
                $('#pending-tasks').text(stats.pending);
                $('#overdue-tasks').text(stats.overdue);
            }
        } catch (error) {
            api.showError(error);
        }
    }

    // Load filter options
    async function loadFilters() {
        try {
            const [categoriesRes, prioritiesRes, statusesRes] = await Promise.all([
                api.getCategories(),
                api.getPriorities(),
                api.getStatuses()
            ]);

            // Populate category filter
            const categorySelect = $('#category_id');
            categoriesRes.data.forEach(category => {
                categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
            });

            // Populate priority filter
            const prioritySelect = $('#priority_id');
            prioritiesRes.data.forEach(priority => {
                prioritySelect.append(`<option value="${priority.id}">${priority.name}</option>`);
            });

            // Populate status filter
            const statusSelect = $('#status_id');
            statusesRes.data.forEach(status => {
                statusSelect.append(`<option value="${status.id}">${status.name}</option>`);
            });
        } catch (error) {
            api.showError(error);
        }
    }

    // Load todos
    async function loadTodos() {
        const container = $('#todos-container');
        container.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">載入中...</span>
                </div>
                <p class="mt-2">載入任務中...</p>
            </div>
        `);

        try {
            console.log('Loading todos with token:', api.token ? 'Yes' : 'No');
            const params = { ...currentFilters, page: currentPage };
            console.log('Request params:', params);
            const response = await api.getTodos(params);
            console.log('API response:', response);
            
            if (response.success) {
                const todos = response.data.data;
                const pagination = response.data;
                
                if (todos.length > 0) {
                    renderTodos(todos);
                    renderPagination(pagination);
                } else {
                    container.html(`
                        <div class="text-center py-5">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">沒有找到任務</h5>
                            <p class="text-muted">建立您的第一個任務開始吧！</p>
                            <a href="{{ route('todos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>建立任務
                            </a>
                        </div>
                    `);
                }
            }
        } catch (error) {
            console.error('Load todos error:', error);
            api.showError(error);
            container.html(`
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="text-danger">載入失敗</h5>
                    <p class="text-muted">無法載入任務，請稍後再試</p>
                    <p class="text-muted small">錯誤詳情: ${error.message || '未知錯誤'}</p>
                    <button class="btn btn-primary" onclick="location.reload()">
                        <i class="fas fa-redo me-1"></i>重新載入
                    </button>
                </div>
            `);
        }
    }

    // Render todos
    function renderTodos(todos) {
        const container = $('#todos-container');
        const template = document.getElementById('todo-item-template');
        
        container.html('<div class="row"></div>');
        const row = container.find('.row');

        todos.forEach(todo => {
            const clone = template.content.cloneNode(true);
            const item = clone.querySelector('.col-12');
            
            // Set todo data
            const titleLink = item.querySelector('.todo-title');
            titleLink.textContent = todo.title;
            titleLink.href = `/todos/${todo.id}`;
            
            const description = item.querySelector('.todo-description');
            if (todo.description) {
                description.textContent = todo.description.length > 100 ? 
                    todo.description.substring(0, 100) + '...' : todo.description;
            } else {
                description.style.display = 'none';
            }
            
            // Set badges
            const badgesContainer = item.querySelector('.todo-badges');
            if (todo.category) {
                badgesContainer.innerHTML += `
                    <span class="badge category-badge" style="background-color: ${todo.category.color}">
                        <i class="fas fa-tag me-1"></i>${todo.category.name}
                    </span>
                `;
            }
            if (todo.priority) {
                badgesContainer.innerHTML += `
                    <span class="badge priority-badge" style="background-color: ${todo.priority.color}">
                        <i class="fas fa-exclamation-triangle me-1"></i>${todo.priority.name}
                    </span>
                `;
            }
            console.log('Todo status debug:', {
                id: todo.id,
                is_completed: todo.is_completed,
                status: todo.status,
                statusExists: !!todo.status
            });
            
            // Check if task is overdue (due date is past and task is not completed)
            const isCompleted = todo.is_completed === true || todo.is_completed === 'true' || todo.is_completed === 1;
            const isCompletedByStatus = todo.status && todo.status.name === 'Completed';
            // Only consider task completed if both is_completed is true AND status is not 'Completed'
            // This allows overdue tasks to show even if they have a 'Completed' status
            const isTaskCompleted = isCompleted;
            
            const dueDate = todo.due_date ? new Date(todo.due_date) : null;
            const currentDate = new Date();
            
            const isOverdue = dueDate && dueDate < currentDate && !isTaskCompleted;
            
            if (isOverdue) {
                // Show overdue status
                badgesContainer.innerHTML += `
                    <span class="badge status-badge" style="background-color: #EF4444">
                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                    </span>
                `;
            } else if (todo.status && todo.status.name) {
                badgesContainer.innerHTML += `
                    <span class="badge status-badge" style="background-color: ${todo.status.color}">
                        <i class="fas fa-signal me-1"></i>${todo.status.name}
                    </span>
                `;
            } else {
                // Show completion status if no status is set
                const completionStatus = isCompleted ? 'Completed' : 'Pending';
                const completionColor = isCompleted ? '#10B981' : '#6B7280';
                const completionIcon = isCompleted ? 'fa-check-circle' : 'fa-clock';
                badgesContainer.innerHTML += `
                    <span class="badge status-badge" style="background-color: ${completionColor}">
                        <i class="fas ${completionIcon} me-1"></i>${completionStatus}
                    </span>
                `;
            }
            
                         // Set info
             const infoContainer = item.querySelector('.todo-info');
             let infoHtml = '';
             if (todo.due_date) {
                 // Parse the ISO date string and convert to local time
                 const dueDate = new Date(todo.due_date);
                 
                 // Format as YYYY-MM-DD HH:mm
                 const year = dueDate.getFullYear();
                 const month = String(dueDate.getMonth() + 1).padStart(2, '0');
                 const day = String(dueDate.getDate()).padStart(2, '0');
                 const hours = String(dueDate.getHours()).padStart(2, '0');
                 const minutes = String(dueDate.getMinutes()).padStart(2, '0');
                 const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}`;
                 infoHtml += `<div><i class="fas fa-calendar me-1"></i>${formattedDateTime}</div>`;
             }
            infoHtml += `<div><i class="fas fa-user me-1"></i>建立者: ${todo.creator.name}</div>`;
            if (todo.assignee) {
                infoHtml += `<div><i class="fas fa-user-check me-1"></i>指派給: ${todo.assignee.name}</div>`;
            }
            infoContainer.innerHTML = infoHtml;
            
            // Set actions
            const toggleBtn = item.querySelector('.toggle-complete');
            const editBtn = item.querySelector('.edit-todo');
            const deleteBtn = item.querySelector('.delete-todo');
            
            // Set data attributes
            toggleBtn.setAttribute('data-todo-id', todo.id);
            toggleBtn.setAttribute('data-completed', todo.is_completed ? '1' : '0');
            
            if (todo.is_completed) {
                item.querySelector('.card').classList.add('completed');
                toggleBtn.querySelector('i').classList.remove('fa-check');
                toggleBtn.querySelector('i').classList.add('fa-undo');
            }
            
            editBtn.href = `/todos/${todo.id}/edit`;
            
            // Add event listeners
            toggleBtn.addEventListener('click', function() {
                toggleComplete(todo.id, this);
            });
            
            deleteBtn.addEventListener('click', function() {
                deleteTodo(todo.id, item);
            });
            
            row.append(item);
        });
    }

    // Render pagination
    function renderPagination(pagination) {
        const container = $('#todos-container');
        const paginationHtml = `
            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        ${pagination.prev_page_url ? 
                            `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">上一頁</a></li>` : 
                            '<li class="page-item disabled"><span class="page-link">上一頁</span></li>'
                        }
                        
                        ${Array.from({length: pagination.last_page}, (_, i) => i + 1).map(page => 
                            `<li class="page-item ${page === pagination.current_page ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${page}">${page}</a>
                            </li>`
                        ).join('')}
                        
                        ${pagination.next_page_url ? 
                            `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">下一頁</a></li>` : 
                            '<li class="page-item disabled"><span class="page-link">下一頁</span></li>'
                        }
                    </ul>
                </nav>
            </div>
        `;
        
        container.append(paginationHtml);
        
        // Add pagination event listeners
        container.find('.pagination .page-link').on('click', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                currentPage = page;
                loadTodos();
            }
        });
    }

    // Toggle completion
    async function toggleComplete(todoId, button) {
        try {
            const response = await api.toggleTodoComplete(todoId);
            if (response.success) {
                const todoItem = $(button).closest('.todo-item');
                const icon = $(button).find('i');
                
                if (response.is_completed) {
                    todoItem.addClass('completed');
                    icon.removeClass('fa-check').addClass('fa-undo');
                    $(button).data('completed', '1');
                } else {
                    todoItem.removeClass('completed');
                    icon.removeClass('fa-undo').addClass('fa-check');
                    $(button).data('completed', '0');
                }
                
                // Reload stats
                loadStats();
                api.showSuccess('任務狀態已更新');
            }
        } catch (error) {
            api.showError(error);
        }
    }

    // Delete todo
    async function deleteTodo(todoId, item) {
        if (!confirm('確定要刪除這個任務嗎？')) {
            return;
        }
        
        try {
            const response = await api.deleteTodo(todoId);
            if (response.success) {
                $(item).fadeOut(300, function() {
                    $(this).remove();
                    loadStats();
                    loadTodos();
                });
                api.showSuccess('任務已刪除');
            }
        } catch (error) {
            api.showError(error);
        }
    }
});
</script>
@endpush
