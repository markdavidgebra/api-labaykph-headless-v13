@extends('front.layout.master')

@section('main_content')
@php
    $setting = App\Models\Setting::where('id',1)->first();
@endphp
<style>
    .registration-success-card .success-icon-wrap {
        width: 88px;
        height: 88px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #0d6b4c 0%, #0f8c5e 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(13, 107, 76, 0.35);
    }
    .registration-success-card .success-icon-wrap i {
        font-size: 40px;
        color: #fff;
    }
    .registration-success-card .success-heading {
        font-size: 28px;
        font-weight: 700;
        color: #232323;
        margin: 0 0 12px 0;
        letter-spacing: -0.02em;
    }
    .registration-success-card .success-lead {
        color: #555;
        font-size: 16px;
        line-height: 1.65;
        margin: 0 0 28px 0;
    }
    .registration-success-card .success-tips {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 28px;
        text-align: left;
        border-left: 4px solid #9e7102;
    }
    .registration-success-card .success-tips p {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    .registration-success-card .success-tips ul {
        margin: 0;
        padding-left: 20px;
        color: #666;
        font-size: 14px;
        line-height: 1.7;
    }
    .registration-success-card .success-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }
    .registration-success-card .btn-success-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #9e7102 0%, #c4920a 100%);
        color: #fff !important;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 14px rgba(158, 113, 2, 0.35);
    }
    .registration-success-card .btn-success-primary:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(158, 113, 2, 0.4);
    }
    .registration-success-card .btn-success-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: #fff;
        color: #1e3a5f;
        border: 2px solid #1e3a5f;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease;
    }
    .registration-success-card .btn-success-outline:hover {
        background: #1e3a5f;
        color: #fff;
    }
</style>

<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Account Created</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('registration') }}">Sign Up</a></li>
                        <li class="breadcrumb-item active">Check Your Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="registration-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-12">
                <div class="registration-form-card registration-success-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="success-icon-wrap">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3 class="success-heading text-center">Thank you for signing up</h3>
                    <p class="success-lead text-center">
                        We’re grateful you’ve chosen to join us. We’ve sent a verification link to your email — click it to activate your account and sign in. We look forward to having you with us.
                    </p>
                    <div class="success-tips">
                        <p>While you wait:</p>
                        <ul>
                            <li>Look for an email from <strong>{{ config('app.name') }}</strong></li>
                            <li>Check your spam or junk folder if you don’t see it in your inbox</li>
                            <li>Click <strong>“Verify my email”</strong> in the message — no need to reply</li>
                        </ul>
                    </div>
                    <div class="success-actions">
                        <a href="{{ route('login') }}" class="btn-success-primary btn-elegant-cta">
                            <span>Go to Login</span>
                            <span class="btn-elegant-cta__icon">
                                <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                            </span>
                        </a>
                        <a href="{{ route('home') }}" class="btn-success-outline btn-elegant-cta">
                            <span>Back to Home</span>
                            <span class="btn-elegant-cta__icon">
                                <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
