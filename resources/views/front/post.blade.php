@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>{{ $post->title }}</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                        <li class="breadcrumb-item active">{{ $post->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="post-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 mb-4 mb-lg-0 wow fadeInUp" data-wow-duration="0.6s">
                <article class="post-article">
                    <div class="post-image-wrapper">
                        <img src="{{ asset('uploads/'.$post->photo) }}" alt="{{ $post->title }}" class="post-featured-image">
                    </div>
                    
                    <div class="post-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Published on {{ $post->created_at->format('M. j, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-folder"></i>
                            <span>Category: <a href="{{ route('category',$post->blog_category->slug) }}">{{ $post->blog_category->name }}</a></span>
                        </div>
                    </div>
                    
                    <div class="post-content elegant-description">
                        {!! $post->description !!}
                    </div>
                </article>
            </div>
            
            <div class="col-lg-4 col-md-12 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.1s">
                <aside class="post-sidebar">
                    <div class="sidebar-widget">
                        <div class="widget-header">
                            <i class="fas fa-newspaper"></i>
                            <h3>Latest Posts</h3>
                        </div>
                        <div class="widget-body">
                            <ul class="latest-posts-list">
                                @foreach($latest_posts as $latest_post)
                                <li class="latest-post-item">
                                    <a href="{{ route('post',$latest_post->slug) }}" class="post-link">
                                        <i class="fas fa-angle-right"></i>
                                        <span>{{ $latest_post->title }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="sidebar-widget">
                        <div class="widget-header">
                            <i class="fas fa-tags"></i>
                            <h3>Categories</h3>
                        </div>
                        <div class="widget-body">
                            <ul class="categories-list">
                                @foreach($categories as $category)
                                <li class="category-item">
                                    <a href="{{ route('category',$category->slug) }}" class="category-link">
                                        <i class="fas fa-angle-right"></i>
                                        <span>{{ $category->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection