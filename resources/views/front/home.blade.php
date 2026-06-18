@extends('front.layout.master')

@section('main_content')
<div class="slider slider-hero">
    <div class="slide-carousel owl-carousel">
        @forelse($sliders as $slider)
        @php
            // use a placeholder color/image when no photo to avoid blank slides
            $slidePhoto = !empty($slider->photo) ? asset('uploads/'.$slider->photo) : null;
            $fallbackClass = $slidePhoto ? '' : 'slider-hero-fallback';
        @endphp
        <div class="item {{ $fallbackClass }}" @if($slidePhoto) style="background-image:url({{ $slidePhoto }});" @endif>
            @if($slidePhoto)
            <img class="slider-hero__bg-img" src="{{ $slidePhoto }}" alt="{{ $slider->heading ?? '' }}">
            @endif
            <div class="bg"></div>
            <div class="text">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center slider-hero__col">
                            <div class="text-wrapper">
                                <div class="text-content slider-animate">
                                    @if($slider->text)
                                    <p class="slider-welcome">{!! $slider->text !!}</p>
                                    @endif
                                    <h2 class="slider-heading">{{ $slider->heading }}</h2>
                                    @if($slider->button_text != '')
                                    <div class="slider-cta mt_20">
                                        <a href="{{ route('contact') }}" class="slider-hero-btn">
                                            <span>{{ $slider->button_text }}</span>
                                            <span class="slider-hero-btn__icon">
                                                <i class="fas fa-arrow-right slider-hero-btn__arrow" aria-hidden="true"></i>
                                                <i class="fas fa-plane slider-hero-btn__plane" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="item slider-hero-fallback">
            <div class="bg"></div>
            <div class="text">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center slider-hero__col">
                            <div class="text-wrapper">
                                <div class="text-content slider-animate">
                                    <h2 class="slider-heading">Welcome</h2>
                                    <div class="slider-cta mt_20">
                                        <a href="{{ route('contact') }}" class="slider-hero-btn">
                                            <span>Contact Us</span>
                                            <span class="slider-hero-btn__icon">
                                                <i class="fas fa-arrow-right slider-hero-btn__arrow" aria-hidden="true"></i>
                                                <i class="fas fa-plane slider-hero-btn__plane" aria-hidden="true"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

@if($welcome_item->status == 'Show')
@php $setting = $setting ?? \App\Models\Setting::where('id',1)->first(); @endphp
<section class="about-hero pt_70 pb_70" aria-labelledby="about-hero-heading">
    @if(!empty($setting->logo))
    <div class="about-hero__bg-logo" style="background-image: url({{ asset('uploads/'.$setting->logo) }});" aria-hidden="true"></div>
    @endif
    <div class="about-hero__bg-pattern" aria-hidden="true"></div>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-1 order-2 mb-4 mb-lg-0">
                <div class="about-hero__content wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay="0.2s">
                    <div class="about-hero__label-wrap">
                        <span class="about-hero__accent"></span>
                        <span class="about-hero__label">About Us</span>
                    </div>
                    <h2 id="about-hero-heading" class="about-hero__title">{{ $welcome_item->heading }}</h2>
                    <div class="about-hero__description">
                        {!! $welcome_item->description !!}
                    </div>
                    @if($welcome_item->button_text != '')
                    <a href="{{ $welcome_item->button_link }}" class="about-hero__btn btn-elegant-cta">
                        <span>{{ $welcome_item->button_text }}</span>
                        <span class="btn-elegant-cta__icon">
                            <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                            <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                        </span>
                    </a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="about-hero__media wow fadeInRight" data-wow-duration="0.8s" data-wow-delay="0.3s">
                    <div class="about-hero__image-wrap">
                        <div class="about-hero__image" style="background-image: url({{ asset('uploads/'.$welcome_item->photo) }});"></div>
                        <div class="about-hero__image-frame"></div>
                        <div class="about-hero__image-shine" aria-hidden="true"></div>
                    </div>
                    <div class="about-hero__media-accent"></div>
                    @if(!empty($welcome_item->video))
                    <a class="about-hero__play" href="https://www.youtube.com/watch?v={{ $welcome_item->video }}" target="_blank" rel="noopener" aria-label="Play our story video">
                        <span class="about-hero__play-icon"></span>
                        <span class="about-hero__play-pulse"></span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if($home_item->destination_status == 'Show')
<section class="destination-section destination-section--home pt_70 pb_70" aria-labelledby="destination-heading">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="heading elegant-heading destination-section__header wow fadeInUp" data-wow-duration="0.6s">
                    <span class="section-label">Explore</span>
                    <h2 id="destination-heading" class="destination-section__title">{{ $home_item->destination_heading }}</h2>
                    <p class="heading-description destination-section__subtitle">
                        {{ $home_item->destination_subheading }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row destination-grid">
            @foreach($destinations as $destination)
            <div class="col-lg-3 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <article class="destination-card animate-hover">
                    <div class="destination-image-wrapper">
                        <a href="{{ route('destination',$destination->slug) }}" class="destination-link" aria-label="Explore {{ $destination->name }}">
                            <img src="{{ asset('uploads/'.$destination->featured_photo) }}" alt="{{ $destination->name }}" class="destination-image" loading="lazy">
                            <div class="destination-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                    <span>Explore Now</span>
                                </div>
                            </div>
                        </a>
                        <div class="destination-badge" aria-hidden="true">
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
                </article>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="see-more-elegant wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.3s">
                    <a href="{{ route('destinations') }}" class="btn-elegant-primary btn-elegant-cta destination-section__cta">
                        <span>View All Destinations</span>
                        <span class="btn-elegant-cta__icon">
                            <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                            <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if($home_item->feature_status == 'Show')
<div class="why-choose pt_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading why-choose-heading wow fadeInUp" data-wow-duration="0.6s">  
                <h2>Why Travel with Labayk</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($features as $feature)
            <div class="col-md-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.15 }}s">
                <div class="inner pb_70 animate-hover">
                    <div class="icon">
                        <i class="{{ $feature->icon }}"></i>
                    </div>
                    <div class="text">
                        <h2>{{ $feature->heading }}</h2>
                        <p class="feature-description">
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

@if($home_item->testimonial_status == 'Show')
<section class="testimonial testimonial-elegant pt_70 pb_70" id="testimonialSection" style="background-image: url({{ asset('uploads/'.$home_item->testimonial_background) }})">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center testimonial-elegant-header wow fadeInDown" data-wow-duration="0.6s" style="color: #ffffff;">
                <span class="testimonial-elegant-label">Testimonials</span>
                <h2 class="testimonial-elegant-title">{{ $home_item->testimonial_heading }}</h2>
                <p class="testimonial-elegant-subtitle">{{ $home_item->testimonial_subheading }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="testimonial-carousel owl-carousel">
                    @foreach($testimonials as $testimonial)
                    <div class="item testimonial-elegant-card animate-hover">
                        <div class="quote">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="testimonial-stars mb-2">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        </div>
                        <div class="description">
                            <p class="testimonial-excerpt">{!! $testimonial->comment !!}</p>
                            <a href="javascript:void(0)" class="testimonial-read-more" data-name="{{ $testimonial->masked_name }}" data-designation="{{ $testimonial->designation }}" data-content-id="testimonial-full-{{ $loop->iteration }}">
                                Read more <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="text">
                            <h4>{{ $testimonial->masked_name }}</h4>
                            <p>{{ $testimonial->designation }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        {{-- Full content outside carousel so IDs are unique (Owl clones slides) --}}
        <div class="d-none" aria-hidden="true">
            @foreach($testimonials as $testimonial)
            <div id="testimonial-full-{{ $loop->iteration }}" class="testimonial-full-content">{!! $testimonial->comment !!}</div>
            @endforeach
        </div>
    </div>

    {{-- Modal for full testimony --}}
    <div class="modal fade" id="testimonialModal" tabindex="-1" aria-labelledby="testimonialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content testimonial-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="testimonialModalLabel"><i class="fas fa-quote-left me-2"></i><span id="testimonialModalName"></span></h5>
                    <button type="button" class="close testimonial-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body testimonial-modal-body" id="testimonialModalBody"></div>
                <div class="modal-footer">
                    <p class="mb-0" id="testimonialModalDesignation"></p>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function() {
        var section = document.getElementById('testimonialSection');
        var modalEl = document.getElementById('testimonialModal');
        if (!section || !modalEl) return;
        section.addEventListener('click', function(e) {
            var link = e.target && (e.target.closest ? e.target.closest('.testimonial-read-more') : null);
            if (!link) return;
            e.preventDefault();
            e.stopPropagation();
            var name = link.getAttribute('data-name');
            var designation = link.getAttribute('data-designation');
            var contentId = link.getAttribute('data-content-id');
            var fullContent = contentId ? document.getElementById(contentId) : null;
            if (fullContent) {
                document.getElementById('testimonialModalName').textContent = name || '';
                document.getElementById('testimonialModalDesignation').textContent = designation || '';
                document.getElementById('testimonialModalBody').innerHTML = fullContent.innerHTML;
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    (new bootstrap.Modal(modalEl)).show();
                } else if (typeof jQuery !== 'undefined' && jQuery(modalEl).modal) {
                    jQuery(modalEl).modal('show');
                } else {
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.id = 'testimonialModalBackdrop';
                    document.body.appendChild(backdrop);
                }
            }
        });
    })();
    </script>
</section>
@endif


@if($home_item->package_status == 'Show')
<div class="package-section pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading elegant-heading wow fadeInUp" data-wow-duration="0.6s">
                    <span class="section-label">Packages</span>
                    <h2>{{ $home_item->package_heading }}</h2>
                    <p class="heading-description">
                        {{ $home_item->package_subheading }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row package-grid">
            @foreach($packages as $item)
            <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
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
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="see-more-elegant wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="0.3s">
                    <a href="{{ route('packages') }}" class="btn-elegant-primary btn-elegant-cta">
                        <span>View All Packages</span>
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
@endif





@if($home_item->blog_status == 'Show')
<div class="blog pt_70 pb_70" style="background: linear-gradient(to bottom, #fdfdfd 0%, #f8f8f8 100%);">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading wow fadeInUp" data-wow-duration="0.6s">
                    <h2>{{ $home_item->blog_heading }}</h2>
                    <p>
                        {{ $home_item->blog_subheading }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.6s" data-wow-delay="{{ $loop->index * 0.1 }}s">
                <div class="item pb_70 animate-hover">
                    <div class="photo">
                        <img src="{{ asset('uploads/'.$post->photo) }}" alt="">
                    </div>
                    <div class="text">
                        <h2>
                            <a href="{{ route('post',$post->slug) }}">{{ $post->title }}</a>
                        </h2>
                        <div class="short-des">
                            <p>
                                {!! $post->short_description !!}
                            </p>
                        </div>
                        <div class="button-style-2 mt_20">
                            <a href="{{ route('post',$post->slug) }}" class="btn-elegant-cta">
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
    </div>
</div>
@endif

@if(($home_item->cta_status ?? 'Show') == 'Show')
{{-- Final CTA Section - Popular Destination / Umrah --}}
<section class="cta-journey pt_70 pb_70" style="background-image: url('{{ asset('uploads/'.($home_item->cta_background ?? 'cta-journey-bg.png')) }}');">
    <div class="cta-journey-overlay"></div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="cta-journey-inner wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="0.2s">
                    <span class="cta-journey-label">{{ $home_item->cta_label ?? 'Explore Destinations' }}</span>
                    <h2 class="cta-journey-title">{{ $home_item->cta_title ?? 'Experience a meaningful Umrah journey—guided with care.' }}</h2>
                    @if($home_item->cta_text ?? null)
                    <div class="cta-journey-text">
                        {!! $home_item->cta_text !!}
                    </div>
                    @else
                    <p class="cta-journey-text">
                    Labayk should be your first choice for <a href="{{ route('packages') }}" class="cta-journey-highlight">affordable Umrah packages from the USA</a>. With years of experience, we help travelers <a href="{{ route('packages') }}" class="cta-journey-highlight">find the best value without compromising service</a>. <a href="{{ route('contact') }}" class="cta-journey-highlight">Click below to see how much Umrah costs from the USA.</a>.
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<style>
/* Slider – ensure visible height and image slides show */
.slider.slider-hero {
    min-height: 400px;
    position: relative;
}
.slider-hero__col {
    width: 92%;
    max-width: 92%;
    margin-left: auto;
    margin-right: auto;
}
.slider-hero .slide-carousel.owl-carousel {
    display: block !important;
    min-height: 400px;
}
.slider-hero .owl-carousel .owl-stage-outer {
    min-height: 400px;
}
.slider-hero .owl-carousel .owl-stage {
    min-height: 400px;
}
.slider-hero .owl-carousel .owl-item {
    min-height: 400px;
    height: 620px !important;
}
.slider-hero .owl-carousel .owl-item .item,
.slider-hero .slide-carousel > .item {
    min-height: 400px;
    height: 620px !important;
    position: relative;
    overflow: hidden;
    background-repeat: no-repeat !important;
    background-size: cover !important;
    background-position: center !important;
}
.slider-hero .owl-carousel .owl-item .item {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
/* Slide image – backup so image always displays */
.slider-hero .item .slider-hero__bg-img {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    object-position: center !important;
    z-index: 0 !important;
    display: block !important;
    animation: sliderBgMove 28s ease-in-out infinite;
}
@keyframes sliderBgMove {
    0%, 100% {
        transform: scale(1) translate(0, 0);
    }
    33% {
        transform: scale(1.06) translate(-1.5%, -0.5%);
    }
    66% {
        transform: scale(1.04) translate(1%, 1.5%);
    }
}
.slider-hero .item .bg {
    z-index: 1;
}
.slider-hero-fallback {
    background: linear-gradient(135deg, #1a5f2a 0%, #2d8b47 50%, #1a3a2e 100%);
    background-size: cover;
    background-position: center;
}

/* Slider text – elegant, engaging, larger */
.slider-hero .slider-welcome {
    font-size: 17px;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.98);
    margin: 0 0 18px 0;
    line-height: 1.5;
    text-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
}
.slider-hero .slider-heading {
    font-size: clamp(40px, 5.5vw, 62px);
    font-weight: 700;
    color: #fff;
    margin: 0 0 32px 0;
    letter-spacing: -0.02em;
    line-height: 1.15;
    text-transform: uppercase;
    text-shadow: 0 2px 24px rgba(0, 0, 0, 0.5), 0 0 60px rgba(0, 0, 0, 0.25);
}
.slider-hero .slider-heading::after {
    content: '';
    display: block;
    width: 80px;
    height: 4px;
    margin: 22px auto 0;
    background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.95), transparent);
    border-radius: 2px;
}

/* Slider entrance animation - runs on each slide change (home page only) */
.slider-animate .slider-welcome {
    animation: slideFadeUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.2s both;
}
.slider-animate .slider-heading {
    animation: slideFadeUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.35s both;
}
.slider-animate .slider-cta {
    animation: slideFadeUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.5s both;
}
@keyframes slideFadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Re-trigger slider animation on Owl slide change */
.slider-hero .owl-item.active .slider-animate .slider-welcome,
.slider-hero .owl-item.active .slider-animate .slider-heading,
.slider-hero .owl-item.active .slider-animate .slider-cta {
    animation: slideFadeUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}
.slider-hero .owl-item.active .slider-animate .slider-welcome { animation-delay: 0.2s; }
.slider-hero .owl-item.active .slider-animate .slider-heading { animation-delay: 0.35s; }
.slider-hero .owl-item.active .slider-animate .slider-cta { animation-delay: 0.5s; }

/* Ensure CTA button stays visible (not stuck at opacity 0 before animation runs) */
.slider-hero .owl-item.active .slider-animate .slider-cta {
    opacity: 1;
}

/* Slider CTA button – background slides left to right on hover, arrow→airplane */
.slider-hero .slider-hero-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 28px;
    background: #1a5f2a;
    border: 2px solid #2d8b47;
    border-radius: 50px;
    color: #fff !important;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: border-color 0.35s ease, box-shadow 0.35s ease, transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.slider-hero .slider-hero-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #2d8b47;
    border-radius: 48px;
    z-index: 0;
    transform: scaleX(0);
    transform-origin: left center;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.slider-hero .slider-hero-btn span:not(.slider-hero-btn__icon),
.slider-hero .slider-hero-btn .slider-hero-btn__icon {
    position: relative;
    z-index: 1;
}
.slider-hero .slider-hero-btn span:not(.slider-hero-btn__icon) {
    transition: color 0.35s ease, transform 0.25s ease;
}
.slider-hero .slider-hero-btn:hover {
    border-color: #3da35d;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}
.slider-hero .slider-hero-btn:hover::before {
    transform: scaleX(1);
}
.slider-hero .slider-hero-btn:hover span:not(.slider-hero-btn__icon) {
    color: #fff;
}
.slider-hero .slider-hero-btn__icon {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.25em;
    height: 1em;
    overflow: hidden;
}
.slider-hero .slider-hero-btn__icon i {
    font-size: 14px;
    transition: opacity 0.25s ease, transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.slider-hero .slider-hero-btn__arrow {
    position: relative;
    opacity: 1;
}
.slider-hero .slider-hero-btn__plane {
    position: absolute;
    left: 0;
    opacity: 0;
    transform: translateX(-120%);
}
.slider-hero .slider-hero-btn:hover .slider-hero-btn__arrow {
    opacity: 0;
    transform: translateX(120%);
}
.slider-hero .slider-hero-btn:hover .slider-hero-btn__plane {
    opacity: 1;
    transform: translateX(0);
}

/* Hide prev/next arrows – use dots below instead */
.slide-carousel.owl-carousel .owl-nav {
    display: none !important;
}

/* Hero carousel dots – below the content, elegant and engaging */
.slide-carousel.owl-carousel .owl-dots {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 32px;
    display: flex !important;
    justify-content: center;
    align-items: center;
    gap: 12px;
    z-index: 10;
    pointer-events: none;
}
.slide-carousel.owl-carousel .owl-dots .owl-dot {
    pointer-events: auto;
    margin: 0;
    padding: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5) !important;
    border: 2px solid rgba(255, 255, 255, 0.8);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-carousel.owl-carousel .owl-dots .owl-dot:hover {
    background: rgba(255, 255, 255, 0.85) !important;
    border-color: rgba(255, 255, 255, 1);
    transform: scale(1.2);
}
.slide-carousel.owl-carousel .owl-dots .owl-dot.active {
    width: 28px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 1) !important;
    border-color: rgba(255, 255, 255, 1);
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
}
.slide-carousel.owl-carousel .owl-dots .owl-dot.active span {
    display: none;
}
@media (max-width: 767px) {
    .slide-carousel.owl-carousel .owl-dots {
        bottom: 24px;
        gap: 10px;
    }
    .slide-carousel.owl-carousel .owl-dots .owl-dot {
        width: 8px;
        height: 8px;
    }
    .slide-carousel.owl-carousel .owl-dots .owl-dot.active {
        width: 22px;
    }
    .slider-hero .slider-welcome {
        font-size: 9px;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }
    .slider-hero .slider-heading {
        font-size: clamp(14px, 4vw, 17px);
        margin-bottom: 10px;
        line-height: 1.2;
    }
    .slider-hero .slider-heading::after {
        width: 24px;
        height: 1.5px;
        margin-top: 8px;
    }
    .slider-hero .slider-cta {
        margin-top: 10px;
    }
    .slider-hero .slider-hero-btn {
        padding: 8px 14px;
        font-size: 12px;
        min-height: 38px;
    }
}

/* About Hero - Elegant Welcome Section */
.about-hero {
    background: linear-gradient(165deg, #fdfcfb 0%, #f8f6f3 35%, #f2efe9 100%);
    position: relative;
    overflow: hidden;
}
.about-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 60%;
    height: 150%;
    background: radial-gradient(ellipse at center, rgba(158, 113, 2, 0.05) 0%, transparent 65%);
    pointer-events: none;
}
.about-hero__bg-logo {
    position: absolute;
    inset: 0;
    z-index: 0;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center right;
    background-attachment: fixed;
    opacity: 0.06;
    pointer-events: none;
}
.about-hero__bg-pattern {
    position: absolute;
    inset: 0;
    z-index: 0;
    opacity: 0.4;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0L30 60M0 30L60 30' stroke='%239e7102' stroke-width='0.15' fill='none'/%3E%3C/svg%3E");
    pointer-events: none;
}
.about-hero__content {
    padding: 28px 0;
    position: relative;
    z-index: 1;
}
.about-hero__label-wrap {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding: 10px 18px 10px 0;
    border-left: 3px solid transparent;
    border-image: linear-gradient(180deg, #9e7102, #b8860b) 1;
    background: linear-gradient(90deg, rgba(158, 113, 2, 0.06) 0%, transparent 100%);
    border-radius: 0 6px 6px 0;
}
.about-hero__accent {
    width: 32px;
    height: 2px;
    background: linear-gradient(90deg, #9e7102, #b8860b);
    border-radius: 2px;
    flex-shrink: 0;
}
.about-hero__label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.24em;
    text-transform: uppercase;
    color: #8a6200;
}
.about-hero__title {
    font-size: clamp(36px, 4.5vw, 50px);
    font-weight: 700;
    color: #1a1a1a;
    letter-spacing: -0.03em;
    line-height: 1.18;
    margin: 0 0 28px 0;
    position: relative;
    padding-bottom: 20px;
}
.about-hero__title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 64px;
    height: 4px;
    background: linear-gradient(90deg, #9e7102, #b8860b);
    border-radius: 2px;
}
.about-hero__description {
    font-size: 18px;
    color: #4a4a4a;
    line-height: 1.95;
    margin-bottom: 32px;
    max-width: 520px;
    padding-left: 20px;
    border-left: 2px solid rgba(158, 113, 2, 0.2);
    transition: border-color 0.3s ease, color 0.3s ease;
}
.about-hero__content:hover .about-hero__description {
    border-left-color: rgba(158, 113, 2, 0.35);
}
.about-hero__description p {
    margin-bottom: 1em;
}
.about-hero__description p:last-child {
    margin-bottom: 0;
}
.about-hero__btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 32px;
    background: linear-gradient(135deg, #9e7102 0%, #8a6200 100%);
    border-radius: 10px;
    color: #fff !important;
    font-weight: 600;
    font-size: 15px;
    letter-spacing: 0.02em;
    text-decoration: none;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 20px rgba(158, 113, 2, 0.28);
}
.about-hero__btn:hover {
    color: #fff !important;
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(158, 113, 2, 0.4);
}
.about-hero__btn i {
    font-size: 12px;
    transition: transform 0.3s ease;
}
.about-hero__btn:hover i {
    transform: translateX(5px);
}

.about-hero__media {
    position: relative;
    z-index: 1;
}
.about-hero__image-wrap {
    position: relative;
    border-radius: 24px;
}
.about-hero__image {
    position: relative;
    min-height: 400px;
    border-radius: 24px;
    background-size: cover;
    background-position: center;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.04);
    transition: transform 0.55s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.5s ease;
}
.about-hero__image::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(0, 0, 0, 0.08) 100%);
    pointer-events: none;
    transition: opacity 0.4s ease;
}
.about-hero__image-shine {
    position: absolute;
    inset: 0;
    border-radius: 24px;
    background: linear-gradient(105deg, transparent 40%, rgba(255, 255, 255, 0.06) 50%, transparent 60%);
    pointer-events: none;
    z-index: 1;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.about-hero__media:hover .about-hero__image-shine {
    opacity: 1;
}
.about-hero__image-frame {
    position: absolute;
    inset: -8px;
    border: 1px solid rgba(158, 113, 2, 0.15);
    border-radius: 28px;
    pointer-events: none;
    z-index: 0;
    transition: opacity 0.4s ease, border-color 0.4s ease;
}
.about-hero__media:hover .about-hero__image {
    transform: scale(1.02);
    box-shadow: 0 32px 80px rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.05);
}
.about-hero__media:hover .about-hero__image-frame {
    opacity: 0.5;
    border-color: rgba(158, 113, 2, 0.25);
}
.about-hero__media-accent {
    position: absolute;
    bottom: -14px;
    right: -14px;
    width: 28%;
    height: 38%;
    border: 2px solid rgba(158, 113, 2, 0.3);
    border-radius: 0 0 24px 0;
    z-index: 0;
    transition: border-color 0.3s ease, transform 0.3s ease;
}
.about-hero__media:hover .about-hero__media-accent {
    border-color: rgba(158, 113, 2, 0.45);
    transform: translate(2px, 2px);
}
.about-hero__play {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 88px;
    height: 88px;
    background: rgba(255, 255, 255, 0.96);
    border-radius: 50%;
    color: #9e7102;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}
.about-hero__play:hover {
    background: #9e7102;
    transform: translate(-50%, -50%) scale(1.1);
    box-shadow: 0 14px 44px rgba(158, 113, 2, 0.4);
}
.about-hero__play-icon {
    position: relative;
    z-index: 1;
    width: 0;
    height: 0;
    margin-left: 6px;
    border-left: 28px solid #9e7102;
    border-top: 17px solid transparent;
    border-bottom: 17px solid transparent;
    transition: border-color 0.3s;
}
.about-hero__play:hover .about-hero__play-icon {
    border-left-color: #fff;
}
.about-hero__play-pulse {
    position: absolute;
    inset: -8px;
    border: 2px solid rgba(158, 113, 2, 0.35);
    border-radius: 50%;
    animation: aboutPlayPulse 2s ease-out infinite;
}
@keyframes aboutPlayPulse {
    0% { transform: scale(0.92); opacity: 1; }
    100% { transform: scale(1.35); opacity: 0; }
}
@media (max-width: 991px) {
    .about-hero__image { min-height: 320px; }
    .about-hero__media-accent { display: none; }
    .about-hero__image-frame { display: none; }
    .about-hero__description { padding-left: 16px; }
}
@media (max-width: 767px) {
    .about-hero__image { min-height: 280px; }
    .about-hero__play { width: 72px; height: 72px; }
    .about-hero__title::after { width: 48px; height: 3px; }
    .about-hero__description { padding-left: 12px; }
    .about-hero__play-icon {
        border-left-width: 22px;
        border-top-width: 14px;
        border-bottom-width: 14px;
    }
}

/* Destination section – home: more elegant and engaging */
.destination-section--home .destination-section__header {
    margin-bottom: 56px;
}
.destination-section--home .elegant-heading .section-label {
    padding: 8px 16px 8px 20px;
    background: linear-gradient(90deg, rgba(158, 113, 2, 0.08) 0%, transparent 100%);
    border-radius: 0 8px 8px 0;
    margin-bottom: 18px;
}
.destination-section--home .destination-section__title {
    position: relative;
    padding-bottom: 20px;
    margin-bottom: 16px;
}
.destination-section--home .destination-section__title::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 72px;
    height: 4px;
    background: linear-gradient(90deg, #9e7102, #b8860b);
    border-radius: 2px;
}
.destination-section--home .destination-section__subtitle {
    font-size: 18px;
    color: #5a5a5a;
    line-height: 1.85;
    max-width: 640px;
    margin: 0 auto;
}
.destination-section--home .destination-card {
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
    transition: transform 0.45s cubic-bezier(0.34, 1.2, 0.64, 1), box-shadow 0.45s ease, border-color 0.3s ease;
}
.destination-section--home .destination-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 24px 48px rgba(158, 113, 2, 0.18), 0 0 0 1px rgba(158, 113, 2, 0.12);
}
.destination-section--home .destination-card::before {
    height: 3px;
    border-radius: 20px 20px 0 0;
    transition: transform 0.45s cubic-bezier(0.34, 1.2, 0.64, 1);
}
.destination-section--home .destination-image-wrapper {
    height: 260px;
}
.destination-section--home .destination-image-wrapper::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(0, 0, 0, 0.12) 100%);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.4s ease;
}
.destination-section--home .destination-card:hover .destination-image-wrapper::after {
    opacity: 1;
}
.destination-section--home .destination-image {
    transition: transform 0.7s cubic-bezier(0.34, 1.2, 0.64, 1);
}
.destination-section--home .destination-card:hover .destination-image {
    transform: scale(1.1) rotate(1deg);
}
.destination-section--home .destination-overlay {
    background: linear-gradient(160deg, rgba(158, 113, 2, 0.88) 0%, rgba(139, 98, 0, 0.82) 100%);
    transition: opacity 0.4s ease;
}
.destination-section--home .overlay-content {
    transform: translateY(16px) scale(0.96);
    transition: transform 0.45s cubic-bezier(0.34, 1.2, 0.64, 1);
}
.destination-section--home .overlay-content i {
    font-size: 36px;
    margin-bottom: 12px;
}
.destination-section--home .overlay-content span {
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.08em;
}
.destination-section--home .destination-content {
    padding: 28px 24px;
    border-radius: 0 0 20px 20px;
}
.destination-section--home .destination-title {
    font-size: 21px;
    margin-bottom: 14px;
}
.destination-section--home .destination-action {
    padding-top: 18px;
    border-top: 1px solid rgba(0, 0, 0, 0.06);
}
.destination-section--home .explore-btn {
    font-size: 14px;
    letter-spacing: 0.06em;
    transition: color 0.3s ease, transform 0.25s ease;
}
.destination-section--home .destination-card:hover .explore-btn {
    transform: translateX(4px);
}
.destination-section--home .see-more-elegant {
    margin-top: 56px;
    padding-top: 40px;
    border-top: 1px solid rgba(158, 113, 2, 0.12);
}
.destination-section--home .destination-section__cta {
    padding: 18px 44px;
    font-size: 15px;
    letter-spacing: 0.04em;
    transition: all 0.35s cubic-bezier(0.34, 1.2, 0.64, 1);
}

.why-choose .feature-description {
    line-height: 1.4 !important;
    letter-spacing: 0.02em;
    text-align: justify;
}
.testimonial-stars .fa-star {
    font-size: 0.9em;
    margin: 0 1px;
}
.testimonial-elegant-header {
    position: relative;
    z-index: 2;
    padding: 24px 16px;
    background: rgba(0,0,0,0.4);
    border-radius: 8px;
    margin-bottom: 32px;
}
.testimonial-elegant-header .testimonial-elegant-label,
.testimonial-elegant-header .testimonial-elegant-title,
.testimonial-elegant-header .testimonial-elegant-subtitle {
    color: #ffffff !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.9), 0 2px 6px rgba(0,0,0,0.8) !important;
}
.testimonial-elegant-header .testimonial-elegant-title {
    font-weight: 800 !important;
}
.testimonial-elegant-header .testimonial-elegant-subtitle {
    font-weight: 600 !important;
    color: #ffffff !important;
}

/* CTA Journey - Popular Destination / Umrah */
.cta-journey {
    position: relative;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
.cta-journey-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
}
.cta-journey .container.position-relative {
    z-index: 2;
}
.cta-journey-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: #e4c547;
    margin-bottom: 12px;
}
.cta-journey-title {
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 20px 0;
    line-height: 1.2;
    text-shadow: 0 2px 8px rgba(0,0,0,0.6);
}
.cta-journey-text {
    font-size: 17px;
    line-height: 1.7;
    color: #ffffff;
    margin: 0;
    max-width: 720px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 0 1px 4px rgba(0,0,0,0.5);
}
.cta-journey-highlight {
    color: #e4c547 !important;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}
.cta-journey-highlight:hover {
    color: #f5d86e !important;
}
</style>

@endsection