@extends('layouts.app')

@section('title', '登入 - ToDo List')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>登入
                </h4>
            </div>
            <div class="card-body p-4">
                <form id="login-form">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>電子郵件
                        </label>
                        <input type="email" class="form-control" 
                               id="email" name="email" required autofocus>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>密碼
                        </label>
                        <input type="password" class="form-control" 
                               id="password" name="password" required>
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            記住我
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-sign-in-alt me-1"></i>登入
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">
                    還沒有帳號？ 
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        <i class="fas fa-user-plus me-1"></i>立即註冊
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form submission
    $('#login-form').on('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>登入中...');
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        try {
            // Collect form data
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password')
            };
            
            // Login via API
            const response = await api.login(data.email, data.password);
            
            if (response.success) {
                api.showSuccess('登入成功！');
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
});
</script>
@endpush
