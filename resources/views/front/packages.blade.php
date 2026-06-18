@extends('front.layout.master')

@section('main_content')
@php
$setting = App\Models\Setting::where('id',1)->first();
@endphp
<div class="page-top" style="background-image: url({{ asset('uploads/'.$setting->banner) }})">
    <div class="container">
        <div class="row">
            <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                <h2>Packages</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Packages</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="packages-listing-section pt_70 pb_70">
    <div class="container">
        <div class="packages-header mb-5 wow fadeInUp" data-wow-duration="0.6s">
            <div class="row align-items-end">
                <div class="col-md-8">
                    <h1 class="page-title">All Packages</h1>
                    <p class="page-subtitle">Discover our curated collection of travel experiences</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="packages-count">{{ $packages->total() }} packages</span>
                </div>
            </div>
        </div>
        
        <div class="filter-bar mb-5 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.1s">
            <form action="{{ route('packages') }}" method="get" class="filter-form-horizontal">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <input type="text" name="name" class="form-control filter-input" placeholder="Search packages..." value="{{ $form_name }}">
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <input type="text" name="min_price" class="form-control filter-input" placeholder="Min price" value="{{ $form_min_price }}">
                    </div>
                    
                    <div class="col-lg-2 col-md-6">
                        <input type="text" name="max_price" class="form-control filter-input" placeholder="Max price" value="{{ $form_max_price }}">
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <select name="destination_id" class="form-select filter-select">
                            <option value="">All Destinations</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" @if($form_destination_id == $destination->id) selected @endif>{{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-2 col-md-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn filter-submit-btn flex-fill">
                                <i class="fas fa-search"></i> Search
                            </button>
                            @if($form_name || $form_min_price || $form_max_price || $form_destination_id || ($form_review && $form_review != 'all'))
                            <a href="{{ route('packages') }}" class="btn filter-reset-btn">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="row package-grid">
                    @foreach($packages as $item)
                    <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.08 }}s">
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
                @if($packages->hasPages())
                <div class="row">
                    <div class="col-md-12">
                        <div class="pagination-wrapper">
                            <div class="elegant-pagination">
                                {{ $packages->appends($_GET)->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
