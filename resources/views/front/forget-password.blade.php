@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Forget Password</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Forget Password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="forget-password-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-12">
                <div class="password-form-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="form-header">
                        <i class="fas fa-key"></i>
                        <h3>Reset Your Password</h3>
                        <p>Enter your email address and we'll send you a link to reset your password.</p>
                    </div>
                    <form action="{{ route('forget_password_submit') }}" method="post" class="elegant-password-form">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="forget_email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Email Address</span>
                            </label>
                            <input type="email" class="form-control elegant-input" id="forget_email" name="email" placeholder="Enter your email address" required>
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" class="btn-submit-password btn-elegant-cta">
                                <span>Send Reset Link</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="form-footer">
                            <a href="{{ route('login') }}" class="back-to-login">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Login Page</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection