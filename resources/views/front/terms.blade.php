@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Terms of Use</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Terms of Use</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="terms-section pt_70 pb_70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="terms-content-card wow fadeInUp animate-hover" data-wow-duration="0.6s">
                    <div class="content-header">
                        <div class="header-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h2>Terms of Use</h2>
                        <p>Please read these terms and conditions carefully before using our services.</p>
                    </div>
                    <div class="content-body elegant-description">
                        {!! $term_privacy_item->term !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection