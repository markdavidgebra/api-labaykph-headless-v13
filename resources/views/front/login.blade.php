@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Login</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Login</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12">
                <div class="login-form-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="form-header">
                        <i class="fas fa-sign-in-alt"></i>
                        <h3>Welcome Back</h3>
                        <p>Sign in to your account to continue</p>
                    </div>
                    <form action="{{ route('login_submit') }}" method="post" class="elegant-login-form">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="login_email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Email Address</span>
                            </label>
                            <input type="email" class="form-control elegant-input" id="login_email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="login_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Password</span>
                            </label>
                            <input type="password" class="form-control elegant-input" id="login_password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="remember-me">
                                    <input type="checkbox" id="remember" name="remember" class="remember-checkbox">
                                    <label for="remember" class="remember-label">Remember me</label>
                                </div>
                                <a href="{{ route('forget_password') }}" class="forget-password-link">
                                    Forget Password?
                                </a>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" class="btn-submit-login btn-elegant-cta">
                                <span>Login</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="form-footer">
                            <p class="footer-text">
                                Don't have an account? 
                                <a href="{{ route('registration') }}" class="register-link">
                                    Create Account
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection