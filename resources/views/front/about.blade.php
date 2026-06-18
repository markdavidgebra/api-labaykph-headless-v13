@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>About Us</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">About Us</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

@if($welcome_item->status == 'Show')
<section class="special special-elegant pt_70 pb_70">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="special-elegant-content wow fadeInLeft" data-wow-duration="0.6s">
                    <span class="special-elegant-label">Welcome</span>
                    <h2 class="special-elegant-title">{{ $welcome_item->heading }}</h2>
                    <div class="special-elegant-description">
                        {!! $welcome_item->description !!}
                    </div>
                    @if($welcome_item->button_text != '')
                    <div class="special-elegant-cta mt_20">
                        <a href="{{ $welcome_item->button_link }}" class="special-elegant-btn">
                            <span>{{ $welcome_item->button_text }}</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="special-elegant-media wow fadeInRight" data-wow-duration="0.6s" data-wow-delay="0.1s" style="background-image: url({{ asset('uploads/'.$welcome_item->photo) }});">
                    <a class="special-elegant-video-btn" href="https://www.youtube.com/watch?v={{ $welcome_item->video }}" target="_blank" rel="noopener" aria-label="Play video"><span></span></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($about_item->feature_status == 'Show')
<div class="why-choose pt_70">
    <div class="container">
        <div class="row">
            @foreach($features as $feature)
            <div class="col-md-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <div class="inner pb_70 animate-hover">
                    <div class="icon">
                        <i class="{{ $feature->icon }}"></i>
                    </div>
                    <div class="text">
                        <h2>{{ $feature->heading }}</h2>
                        <p>
                            {!! $feature->description !!}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($counter_item->status == 'Show')
<div class="counter-section pt_70 pb_70">
    <div class="container">
        <div class="row counter-items">
            <div class="col-md-3 counter-item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.1s">
                <div class="counter">{{ $counter_item->item1_number }}</div>
                <div class="text">{{ $counter_item->item1_text }}</div>
            </div>
            <div class="col-md-3 counter-item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.2s">
                <div class="counter">{{ $counter_item->item2_number }}</div>
                <div class="text">{{ $counter_item->item2_text }}</div>
            </div>
            <div class="col-md-3 counter-item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.3s">
                <div class="counter">{{ $counter_item->item3_number }}</div>
                <div class="text">{{ $counter_item->item3_text }}</div>
            </div>
            <div class="col-md-3 counter-item wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.4s">
                <div class="counter">{{ $counter_item->item4_number }}</div>
                <div class="text">{{ $counter_item->item4_text }}</div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection