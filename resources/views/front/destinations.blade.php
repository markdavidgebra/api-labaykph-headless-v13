@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Destinations</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Destinations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="destination-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading elegant-heading wow fadeInUp" data-wow-duration="0.6s">
                    <span class="section-label">All Destinations</span>
                    <h2>Discover Amazing Places</h2>
                    <p class="heading-description">
                        Explore our curated collection of breathtaking destinations around the world
                    </p>
                </div>
            </div>
        </div>
        <div class="row destination-grid">
            @foreach($destinations as $destination)
            <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <div class="destination-card animate-hover">
                    <div class="destination-image-wrapper">
                        <a href="{{ route('destination',$destination->slug) }}" class="destination-link">
                            <img src="{{ asset('uploads/'.$destination->featured_photo) }}" alt="{{ $destination->name }}" class="destination-image">
                            <div class="destination-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Explore Now</span>
                                </div>
                            </div>
                        </a>
                        <div class="destination-badge">
                            <i class="fas fa-compass"></i>
                        </div>
                    </div>
                    <div class="destination-content">
                        <h3 class="destination-title">
                            <a href="{{ route('destination',$destination->slug) }}">{{ $destination->name }}</a>
                        </h3>
                        <div class="destination-action">
                            <a href="{{ route('destination',$destination->slug) }}" class="explore-btn btn-elegant-cta">
                                <span>Discover</span>
                                <span class="btn-elegant-cta__icon">
                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($destinations->hasPages())
        <div class="row">
            <div class="col-md-12">
                <div class="pagination-wrapper">
                    <div class="elegant-pagination">
                        {{ $destinations->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection