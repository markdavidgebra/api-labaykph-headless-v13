@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Contact Us</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Contact Us</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="contact-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header-wrapper text-center mb-5 wow fadeInUp" data-wow-duration="0.6s">
                    <h2 class="section-title">Get In Touch</h2>
                    <p class="section-description">
                        We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-lg-0 wow fadeInLeft" data-wow-duration="0.6s">
                <div class="contact-form-card animate-hover">
                    <h3 class="form-title">Send Us a Message</h3>
                    <form action="{{ route('contact_submit') }}" method="post" class="elegant-contact-form">
                        @csrf
                        <div class="form-group">
                            <label for="contact_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control elegant-input" id="contact_name" name="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control elegant-input" id="contact_email" name="email" placeholder="Enter your email address" required>
                        </div>
                        <div class="form-group">
                            <label for="contact_message" class="form-label">Message</label>
                            <textarea class="form-control elegant-textarea" id="contact_message" rows="6" name="comment" placeholder="Tell us what's on your mind..." required></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn-submit-contact btn-elegant-cta">
                                <span>Send Message</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 wow fadeInRight" data-wow-duration="0.6s" data-wow-delay="0.1s">
                <div class="addresses-card animate-hover">
                    <h3 class="addresses-title">Our Offices</h3>
                    <div class="addresses-list">
                        @forelse($contact_offices as $office)
                        <div class="address-item">
                            <h4 class="address-office">{{ $office->name }}</h4>
                            <div class="address-content">
                                @if($office->address)
                                <p class="address-text">
                                    {!! nl2br(e($office->address)) !!}
                                </p>
                                @endif
                                <div class="contact-info">
                                    @if($office->landline)<p><strong>Landline:</strong> {{ $office->landline }}</p>@endif
                                    @if($office->globe)<p><strong>Globe:</strong> {{ $office->globe }}</p>@endif
                                    @if($office->smart)<p><strong>Smart:</strong> {{ $office->smart }}</p>@endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">No offices added yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection