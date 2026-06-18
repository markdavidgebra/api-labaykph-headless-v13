@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Create Account</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Create Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="registration-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12">
                <div class="registration-form-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="form-header">
                        <i class="fas fa-user-plus"></i>
                        <h3>Create Your Account</h3>
                        <p>Join us today and start your travel journey</p>
                    </div>
                    <form action="{{ route('registration_submit') }}" method="post" class="elegant-registration-form">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="reg_name" class="form-label">
                                <i class="fas fa-user"></i>
                                <span>Full Name <span class="required">*</span></span>
                            </label>
                            <input type="text" class="form-control elegant-input" id="reg_name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="reg_email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Email Address <span class="required">*</span></span>
                            </label>
                            <input type="email" class="form-control elegant-input" id="reg_email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="reg_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Password <span class="required">*</span></span>
                            </label>
                            <input type="password" class="form-control elegant-input" id="reg_password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="reg_confirm_password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Confirm Password <span class="required">*</span></span>
                            </label>
                            <input type="password" class="form-control elegant-input" id="reg_confirm_password" name="retype_password" placeholder="Confirm your password" required>
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" class="btn-submit-registration btn-elegant-cta">
                                <span>Create Account</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="form-footer">
                            <p class="footer-text">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="login-link">
                                    Login Now
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