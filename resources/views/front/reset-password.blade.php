@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Reset Password</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reset Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="reset-password-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12">
                <div class="password-form-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="form-header">
                        <i class="fas fa-key"></i>
                        <h3>Reset Your Password</h3>
                        <p>Enter your new password below</p>
                    </div>
                    <form action="{{ route('reset_password_submit',[$token,$email]) }}" method="post" class="elegant-password-form">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="reset_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>New Password <span class="required">*</span></span>
                            </label>
                            <div class="password-input-wrap">
                                <input type="password" class="form-control elegant-input" id="reset_password" name="password" placeholder="Enter your new password" required>
                                <button type="button" class="password-toggle-btn" data-target="reset_password" aria-label="Show password">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="reset_retype_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Confirm Password <span class="required">*</span></span>
                            </label>
                            <div class="password-input-wrap">
                                <input type="password" class="form-control elegant-input" id="reset_retype_password" name="retype_password" placeholder="Confirm your new password" required>
                                <button type="button" class="password-toggle-btn" data-target="reset_retype_password" aria-label="Show confirm password">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" class="btn-submit-password btn-elegant-cta">
                                <span>Reset Password</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.password-toggle-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var input = document.getElementById(button.getAttribute('data-target'));
        if (!input) return;

        var isVisible = input.type === 'text';
        input.type = isVisible ? 'password' : 'text';

        var icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-eye', isVisible);
            icon.classList.toggle('fa-eye-slash', !isVisible);
        }

        button.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
    });
});
</script>
@endsection