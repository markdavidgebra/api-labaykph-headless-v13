@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>{{ $destination->name }}</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('destinations') }}">Destinations</a></li>
                        <li class="breadcrumb-item active">{{ $destination->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="destination-detail-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="destination-hero mb_60 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('uploads/'.$destination->featured_photo) }}" alt="{{ $destination->name }}" class="hero-image">
                        <div class="hero-overlay">
                            <div class="hero-content">
                                <h1 class="hero-title">{{ $destination->name }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="content-section mb_60 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.1s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-info-circle"></i></span>
                        <h2 class="section-title">About This Destination</h2>
                    </div>
                    <div class="section-content elegant-description">
                        {!! $destination->description !!}
                    </div>
                </div>


                @if($packages->count()>0)
                <div class="content-section mb_60 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.1s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-suitcase-rolling"></i></span>
                        <h2 class="section-title">Available Packages</h2>
                    </div>
                    <div class="package-section-inner">
                        <div class="row package-grid">
                            @foreach($packages as $item)
                            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="{{ $loop->index * 0.08 }}s">
                                <div class="package-card animate-hover">
                                    <div class="package-image-wrapper">
                                        <a href="{{ route('package',$item->slug) }}" class="package-link">
                                            <img src="{{ asset('uploads/'.$item->featured_photo) }}" alt="{{ $item->name }}" class="package-image">
                                            <div class="package-overlay">
                                                <div class="overlay-content">
                                                    <i class="fas fa-suitcase-rolling"></i>
                                                    <span>View Details</span>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="package-badge">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div class="wishlist-btn">
                                            <a href="{{ route('wishlist',$item->id) }}" class="wishlist-link">
                                                <i class="far fa-heart"></i>
                                            </a>
                                        </div>
                                        @if(!$item->show_sold_out && $item->old_price != '')
                                        <div class="discount-badge">
                                            <span>{{ round((($item->old_price - $item->price) / $item->old_price) * 100) }}% OFF</span>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="package-content">
                                        <div class="package-price-wrapper">
                                            @if($item->show_sold_out)
                                            <div class="package-sold-out-text">SOLD OUT</div>
                                            @else
                                            <div class="package-price">
                                                <span class="currency">$</span>
                                                <span class="amount">{{ $item->price }}</span>
                                            </div>
                                            @if($item->old_price != '')
                                            <div class="package-old-price">
                                                <del>${{ $item->old_price }}</del>
                                            </div>
                                            @endif
                                            @endif
                                        </div>
                                        <h3 class="package-title">
                                            <a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a>
                                        </h3>
                                        <div class="package-rating">
                                            @if($item->total_score || $item->total_rating)
                                                @php
                                                $rating = $item->total_score/$item->total_rating;
                                                @endphp
                                                <div class="stars">
                                                    @for($i=1; $i<=5; $i++)
                                                        @if($i <= $rating)
                                                            <i class="fas fa-star"></i>
                                                        @elseif($i-0.5 <= $rating)
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="review-count">({{ $item->reviews->count() }} Reviews)</span>
                                            @else
                                                <div class="stars">
                                                    @for($i=1; $i<=5; $i++)
                                                        <i class="far fa-star"></i>
                                                    @endfor
                                                </div>
                                                <span class="review-count">({{ $item->reviews->count() }} Reviews)</span>
                                            @endif
                                        </div>
                                        <div class="package-features">
                                            <div class="feature-item">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $item->destination->name }}</span>
                                            </div>
                                            <div class="feature-item">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>{{ $item->package_itineraries->count() }} Days</span>
                                            </div>
                                            <div class="feature-item">
                                                <i class="fas fa-users"></i>
                                                <span>{{ $item->tours->count() }} Tours</span>
                                            </div>
                                            <div class="feature-item">
                                                <i class="fas fa-star"></i>
                                                <span>{{ $item->package_amenities->count() }} Amenities</span>
                                            </div>
                                        </div>
                                        <div class="package-action">
                                            <a href="{{ route('package',$item->slug) }}" class="package-btn">
                                                <span>Explore Package</span>
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                

                @if($destination->country != '' || $destination->language != '' || $destination->currency != '' || $destination->area != '' || $destination->timezone != '' || $destination->visa_requirement != '' || $destination->activity != '' || $destination->best_time != '' || $destination->health_safety != '')
                <div class="content-section mb_60 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-clipboard-list"></i></span>
                        <h2 class="section-title">Destination Information</h2>
                    </div>
                    <div class="info-table-wrapper">
                        <div class="table-responsive">
                            <table class="elegant-info-table">
                                @if($destination->country != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-flag"></i> <span>Country</span></td>
                                    <td class="info-value">{{ $destination->country }}</td>
                                </tr>
                                @endif
                                @if($destination->language != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-language"></i> <span>Languages Spoken</span></td>
                                    <td class="info-value">{{ $destination->language }}</td>
                                </tr>
                                @endif
                                @if($destination->currency != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-dollar-sign"></i> <span>Currency Used</span></td>
                                    <td class="info-value">{{ $destination->currency }}</td>
                                </tr>
                                @endif
                                @if($destination->area != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-ruler-combined"></i> <span>Area</span></td>
                                    <td class="info-value">{{ $destination->area }}</td>
                                </tr>
                                @endif
                                @if($destination->timezone != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-clock"></i> <span>Time Zone</span></td>
                                    <td class="info-value">{{ $destination->timezone }}</td>
                                </tr>
                                @endif
                                @if($destination->visa_requirement != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-passport"></i> <span>Visa Requirements</span></td>
                                    <td class="info-value">{!! $destination->visa_requirement !!}</td>
                                </tr>
                                @endif
                                @if($destination->activity != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-hiking"></i> <span>Activities</span></td>
                                    <td class="info-value">{!! $destination->activity !!}</td>
                                </tr>
                                @endif
                                @if($destination->best_time != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-calendar-check"></i> <span>Best Time to Visit</span></td>
                                    <td class="info-value">{!! $destination->best_time !!}</td>
                                </tr>
                                @endif
                                @if($destination->health_safety != '')
                                <tr>
                                    <td class="info-label"><i class="fas fa-shield-alt"></i> <span>Health and Safety</span></td>
                                    <td class="info-value">{!! $destination->health_safety !!}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if($destination_photos->count() > 0)
                <div class="content-section mb_60 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-images"></i></span>
                        <h2 class="section-title">Photo Gallery</h2>
                    </div>
                    <div class="photo-gallery">
                        <div class="row">
                            @foreach($destination_photos as $photo)
                            <div class="col-md-6 col-lg-3 mb-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="{{ $loop->index * 0.05 }}s">
                                <div class="gallery-item animate-hover">
                                    <a href="{{ asset('uploads/'.$photo->photo) }}" class="gallery-link magnific">
                                        <img src="{{ asset('uploads/'.$photo->photo) }}" alt="Destination Photo" class="gallery-image">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($destination_videos->count() > 0)
                <div class="content-section mb_60 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-video"></i></span>
                        <h2 class="section-title">Videos</h2>
                    </div>
                    <div class="video-gallery">
                        <div class="row">
                            @foreach($destination_videos->filter(fn($v) => $v->youtube_video_id) as $video)
                            <div class="col-md-6 col-lg-6 mb-4 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="{{ $loop->index * 0.08 }}s">
                                <div class="video-item animate-hover">
                                    <a class="video-button" href="https://www.youtube.com/watch?v={{ $video->youtube_video_id }}">
                                        <img src="https://img.youtube.com/vi/{{ $video->youtube_video_id }}/0.jpg" alt="Video Thumbnail" class="video-thumbnail">
                                        <div class="video-overlay">
                                            <div class="play-icon">
                                                <i class="fas fa-play"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($destination->map != '')
                <div class="content-section wow fadeInUp" data-wow-duration="0.6s">
                    <div class="section-header">
                        <span class="section-icon"><i class="fas fa-map-marked-alt"></i></span>
                        <h2 class="section-title">Location Map</h2>
                    </div>
                    <div class="map-wrapper">
                        {!! $destination->map !!}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection