@extends('layouts.app')

@section('title', '註冊 - ToDo List')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>註冊
                </h4>
            </div>
            <div class="card-body p-4">
                <form id="register-form">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-1"></i>姓名
                        </label>
                        <input type="text" class="form-control" 
                               id="name" name="name" required autofocus>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>電子郵件
                        </label>
                        <input type="email" class="form-control" 
                               id="email" name="email" required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>密碼
                        </label>
                        <input type="password" class="form-control" 
                               id="password" name="password" required>
                        <div class="invalid-feedback" id="password-error"></div>
                        <div class="form-text">
                            密碼至少需要8個字元。
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-1"></i>確認密碼
                        </label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                        <div class="invalid-feedback" id="password_confirmation-error"></div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success" id="submit-btn">
                            <i class="fas fa-user-plus me-1"></i>註冊
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">
                    已經有帳號了？ 
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-sign-in-alt me-1"></i>立即登入
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
    $('#register-form').on('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        // Disable submit button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>註冊中...');
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        try {
            // Collect form data
            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirmation: formData.get('password_confirmation')
            };
            
            // Register via API
            const response = await api.register(data.name, data.email, data.password, data.password_confirmation);
            
            if (response.success) {
                api.showSuccess('註冊成功！');
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
