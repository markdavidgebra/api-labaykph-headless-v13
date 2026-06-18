@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <!-- <h2>Latest News</h2> -->
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Latest News</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="blog-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header-wrapper text-center mb-5 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="elegant-heading">
                        <!-- <span class="section-label">Latest News</span> -->
                        <h2>Latest News</h2>
                        <p class="heading-description">
                            Stay updated with our latest travel tips, stories, and insights
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row blog-grid">
            @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <div class="blog-card animate-hover">
                    <div class="blog-image-wrapper">
                        <a href="{{ route('post',$post->slug) }}" class="blog-image-link">
                            <img src="{{ asset('uploads/'.$post->photo) }}" alt="{{ $post->title }}" class="blog-image">
                            <div class="blog-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-book-open"></i>
                                    <span>Read Article</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="blog-content">
                        <h3 class="blog-title">
                            <a href="{{ route('post',$post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <div class="blog-excerpt">
                            {!! $post->short_description !!}
                        </div>
                        <div class="blog-action">
                            <a href="{{ route('post',$post->slug) }}" class="read-more-btn btn-elegant-cta">
                                <span>Read More</span>
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
        @if($posts->hasPages())
        <div class="row">
            <div class="col-md-12">
                <div class="pagination-wrapper">
                    <div class="elegant-pagination">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection