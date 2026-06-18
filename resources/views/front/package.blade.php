@extends('front.layout.master')

@section('main_content')
    <div class="page-top page-top-package" style="background-image: url({{ asset('uploads/' . $package->banner) }})">
        <div class="container">
            <div class="row">
                <div class="col-md-12 wow fadeInDown" data-wow-duration="0.5s">
                    <h2>{{ $package->name }}</h2>
                    <h3><i class="fas fa-plane-departure"></i> {{ $package->destination->name }}</h3>

                    @if ($package->total_score || $package->total_rating)
                        <div class="review">
                            <div class="set">
                                @php
                                    $package_rating = $package->total_score / $package->total_rating;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $package_rating)
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $package_rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span>({{ $package_rating }} out of 5)</span>
                        </div>
                    @else
                        <div class="review">
                            <div class="set">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
                            </div>
                            <span>(No Rating Found)</span>
                        </div>
                    @endif

                    <div class="price">
                        @if($package->show_sold_out)
                            <span class="package-detail-sold-out">SOLD OUT</span>
                        @else
                            ${{ $package->price }} @if ($package->old_price != '')
                                <del>${{ $package->old_price }}</del>
                            @endif
                        @endif
                    </div>
                    <div class="person">
                        per person
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="package-detail-section pt_80 pb_80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 wow fadeInUp" data-wow-duration="0.6s">
                    <div class="package-tabs-wrapper mb_50">
                        <ul class="nav elegant-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-8" data-bs-toggle="tab" data-bs-target="#tab-8-pane"
                                    type="button" role="tab" aria-controls="tab-8-pane"
                                    aria-selected="true">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Booking</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-1" data-bs-toggle="tab"
                                    data-bs-target="#tab-1-pane" type="button" role="tab" aria-controls="tab-1-pane"
                                    aria-selected="false">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Detail</span>
                                </button>
                            </li>

                            @if ($package_itineraries->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-2" data-bs-toggle="tab"
                                        data-bs-target="#tab-2-pane" type="button" role="tab"
                                        aria-controls="tab-2-pane" aria-selected="false">
                                        <i class="fas fa-route"></i>
                                        <span>Itinerary</span>
                                    </button>
                                </li>
                            @endif

                            @if ($package->map != '')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-3" data-bs-toggle="tab"
                                        data-bs-target="#tab-3-pane" type="button" role="tab"
                                        aria-controls="tab-3-pane" aria-selected="false">
                                        <i class="fas fa-map-marked-alt"></i>
                                        <span>Location</span>
                                    </button>
                                </li>
                            @endif

                            @if ($package_photos->count() > 0 || $package_videos->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-4" data-bs-toggle="tab"
                                        data-bs-target="#tab-4-pane" type="button" role="tab"
                                        aria-controls="tab-4-pane" aria-selected="false">
                                        <i class="fas fa-images"></i>
                                        <span>Gallery</span>
                                    </button>
                                </li>
                            @endif

                            @if ($package_faqs->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-5" data-bs-toggle="tab"
                                        data-bs-target="#tab-5-pane" type="button" role="tab"
                                        aria-controls="tab-5-pane" aria-selected="false">
                                        <i class="fas fa-question-circle"></i>
                                        <span>FAQ</span>
                                    </button>
                                </li>
                            @endif

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-6" data-bs-toggle="tab" data-bs-target="#tab-6-pane"
                                    type="button" role="tab" aria-controls="tab-6-pane"
                                    aria-selected="false">
                                    <i class="fas fa-star"></i>
                                    <span>Review</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tab-7" data-bs-toggle="tab" data-bs-target="#tab-7-pane"
                                    type="button" role="tab" aria-controls="tab-7-pane"
                                    aria-selected="false">
                                    <i class="fas fa-envelope"></i>
                                    <span>Enquiry</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content elegant-tab-content" id="myTabContent">

                            <div class="tab-pane fade show active" id="tab-8-pane" role="tabpanel" aria-labelledby="tab-8"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    @if ($tours->count() > 0)
                                    <form action="{{ route('payment') }}" method="post" class="booking-form">
                                        @csrf
                                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-12 mb-4">
                                                    <div class="content-section">
                                                    <div class="section-header">
                                                        <span class="section-icon"><i class="fas fa-calendar-alt"></i></span>
                                                        <h2 class="section-title">Available Tours</h2>
                                                        <p class="text-muted small mb-0">Select one tour before contacting sales. Your selection will be sent to our team.</p>
                                                    </div>
                                                    <div class="tours-list tours-compact-elegant">
                                                        @php $i=0; $first_available_tour_id = null; @endphp
                                                        @foreach ($tours as $item)
                                                        @if ($item->booking_end_date < date('Y-m-d'))
                                                        @continue
                                                        @endif
                                                        @php
                                                        $i++;
                                                        $total_booked_seats = 0;
                                                        $all_data = App\Models\Booking::where('tour_id', $item->id)
                                                            ->where('package_id', $package->id)
                                                            ->get();
                                                        foreach ($all_data as $data) {
                                                            $total_booked_seats += $data->total_person;
                                                        }

                                                        if ($item->total_seat == '-1') {
                                                            $remaining_seats = 'Unlimited';
                                                            $is_sold_out = false;
                                                        } else {
                                                            $remaining_seats = $item->total_seat - $total_booked_seats;
                                                            $is_sold_out = ($remaining_seats <= 0);
                                                        }
                                                        if (!$is_sold_out && $first_available_tour_id === null) {
                                                            $first_available_tour_id = $item->id;
                                                        }
                                                        @endphp
                                                        <div class="tour-card {{ $is_sold_out ? 'tour-sold-out' : '' }}">
                                                            @if($is_sold_out)
                                                            <span class="tour-sold-out-badge">SOLD OUT</span>
                                                            @endif
                                                            <div class="tour-card-inner">
                                                                <div class="tour-checkbox">
                                                                    <input type="checkbox" class="form-check-input booking-tour-checkbox" name="tour_id" value="{{ $item->id }}" id="tour_{{ $item->id }}" @if($first_available_tour_id == $item->id) checked @endif {{ $is_sold_out ? 'disabled' : '' }}>
                                                                    <label for="tour_{{ $item->id }}"><span class="tour-number">Tour {{ $i }}</span></label>
                                                                </div>
                                                                <div class="tour-meta">
                                                                    <span class="tour-dates">{{ \Carbon\Carbon::parse($item->tour_start_date)->format('M. j, Y') }} → {{ \Carbon\Carbon::parse($item->tour_end_date)->format('M. j, Y') }}</span>
                                                                    <span class="tour-sep">·</span>
                                                                    <span class="tour-deadline">Book by {{ \Carbon\Carbon::parse($item->booking_end_date)->format('M. j, Y') }}</span>
                                                                    <span class="tour-sep">·</span>
                                                                    <span class="tour-seats">
                                                                        @if ($item->total_seat == -1)
                                                                        Unlimited
                                                                        @else
                                                                        {{ $remaining_seats }} left
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                        @if($i == 0)
                                                        <div class="empty-state">
                                                            <i class="fas fa-calendar-times"></i>
                                                            <h3>No Active Tours</h3>
                                                            <p>All tours for this package have passed their booking deadline. Please check back for new tours.</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12">
                                                <div class="booking-sidebar">
                                                    <div class="sidebar-header">
                                                        <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
                                                    </div>
                                                    <div class="payment-form">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Number of Persons</label>
                                                            <input type="hidden" name="ticket_price" id="ticketPrice" value="{{ $package->price }}">
                                                            <input type="number" min="1" max="100" name="total_person" class="form-control elegant-input" value="1" id="numPersons" oninput="calculateTotal()" @if($i == 0) disabled @endif required>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Total Amount</label>
                                                            <div class="total-amount-display">
                                                                <span class="currency">$</span>
                                                                <span class="amount" id="totalAmount">{{ $package->price }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-3">
                                                            <label class="form-label">Payment Method</label>
                                                            <select name="payment_method" class="form-select elegant-select" @if($i == 0) disabled @endif required>
                                                                {{-- <option value="PayPal">PayPal</option>
                                                                <option value="Stripe">Stripe</option> --}}
                                                                <option value="Cash">Cash</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            @if($i > 0)
                                                            @if (Auth::guard('web')->check())
                                                            <button type="submit" class="btn-book-now btn-elegant-cta">
                                                                <span>Contact to Sales</span>
                                                                <span class="btn-elegant-cta__icon">
                                                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                                                </span>
                                                            </button>
                                                            @else
                                                            <a href="{{ route('login') }}" class="btn-login-to-book btn-elegant-cta">
                                                                <span>Login to Book</span>
                                                                <span class="btn-elegant-cta__icon">
                                                                    <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                                                    <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                                                </span>
                                                            </a>
                                                            @endif
                                                            @else
                                                            <div class="alert alert-warning" style="padding: 15px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; color: #856404;">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                <span style="margin-left: 8px;">No active tours available for booking</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            function calculateTotal() {
                                                const ticketPrice = document.getElementById('ticketPrice').value;
                                                const numPersons = document.getElementById('numPersons').value;
                                                const totalAmount = ticketPrice * numPersons;
                                                document.getElementById('totalAmount').textContent = totalAmount;
                                            }
                                        </script>
                                    </form>
                                    @else
                                    <div class="content-section">
                                        <div class="empty-state">
                                            <i class="fas fa-calendar-times"></i>
                                            <h3>No Tours Available</h3>
                                            <p>There are currently no available tours for this package. Please check back later.</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-1-pane" role="tabpanel"
                                aria-labelledby="tab-1" tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-info-circle"></i></span>
                                            <h2 class="section-title">Package Details</h2>
                                        </div>
                                        <div class="section-content elegant-description">
                                            {!! $package->description !!}
                                        </div>
                                    </div>

                                    @if ($package_amenities_include->count() > 0)
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-check-circle"></i></span>
                                            <h2 class="section-title">What's Included</h2>
                                        </div>
                                        <div class="amenities-grid">
                                            @foreach ($package_amenities_include as $item)
                                            <div class="amenity-item included">
                                                <i class="fas fa-check"></i>
                                                <span>{{ $item->amenity->name }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if ($package_amenities_exclude->count() > 0)
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-times-circle"></i></span>
                                            <h2 class="section-title">What's Excluded</h2>
                                        </div>
                                        <div class="amenities-grid">
                                            @foreach ($package_amenities_exclude as $item)
                                            <div class="amenity-item excluded">
                                                <i class="fas fa-times"></i>
                                                <span>{{ $item->amenity->name }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-2-pane" role="tabpanel" aria-labelledby="tab-2"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-route"></i></span>
                                            <h2 class="section-title">Travel Itinerary</h2>
                                        </div>
                                        <div class="itinerary-timeline">
                                            @foreach ($package_itineraries as $item)
                                            <div class="timeline-item">
                                                <div class="timeline-marker">
                                                    <i class="fas fa-map-pin"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <h3 class="timeline-title">{{ $item->name }}</h3>
                                                    <div class="timeline-description">
                                                        {!! $item->description !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="tab-3-pane" role="tabpanel" aria-labelledby="tab-3"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-map-marked-alt"></i></span>
                                            <h2 class="section-title">Location Map</h2>
                                        </div>
                                        <div class="map-wrapper">
                                            {!! $package->map !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-4-pane" role="tabpanel" aria-labelledby="tab-4"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    @if ($package_photos->count() > 0)
                                    <div class="content-section mb-4">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-images"></i></span>
                                            <h2 class="section-title">Photo Gallery</h2>
                                        </div>
                                        <div class="elegant-photo-gallery">
                                            <div class="row">
                                                @foreach ($package_photos as $item)
                                                <div class="col-md-6 col-lg-3 mb-4">
                                                    <div class="gallery-item">
                                                        <a href="{{ asset('uploads/' . $item->photo) }}" class="gallery-link magnific">
                                                            <img src="{{ asset('uploads/' . $item->photo) }}" alt="Package Photo" class="gallery-image">
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

                                    @if ($package_videos->count() > 0)
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-video"></i></span>
                                            <h2 class="section-title">Video Gallery</h2>
                                        </div>
                                        <div class="elegant-video-gallery">
                                            <div class="row">
                                                @foreach ($package_videos->filter(fn($v) => $v->youtube_video_id) as $item)
                                                <div class="col-md-6 col-lg-6 mb-4">
                                                    <div class="video-item">
                                                        <a class="video-button" href="https://www.youtube.com/watch?v={{ $item->youtube_video_id }}">
                                                            <img src="https://img.youtube.com/vi/{{ $item->youtube_video_id }}/0.jpg" alt="Video Thumbnail" class="video-thumbnail">
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
                                </div>
                            </div>


                            <div class="tab-pane fade" id="tab-5-pane" role="tabpanel" aria-labelledby="tab-5"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-question-circle"></i></span>
                                            <h2 class="section-title">Frequently Asked Questions</h2>
                                        </div>
                                        <div class="elegant-accordion">
                                            <div class="accordion" id="accordionExample">
                                                @foreach ($package_faqs as $item)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading_{{ $loop->iteration }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse_{{ $loop->iteration }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapse_{{ $loop->iteration }}">
                                                            <i class="fas fa-question-circle"></i>
                                                            <span>{{ $item->question }}</span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse_{{ $loop->iteration }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="heading_{{ $loop->iteration }}"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <i class="fas fa-info-circle"></i>
                                                            <div class="answer-content">
                                                                {!! $item->answer !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane fade" id="tab-6-pane" role="tabpanel" aria-labelledby="tab-6"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-star"></i></span>
                                            <h2 class="section-title">Customer Reviews ({{ $reviews->count() }})</h2>
                                        </div>
                                        <div class="reviews-list">
                                            @forelse($reviews as $item)
                                            <div class="review-card">
                                                <div class="review-header">
                                                    <div class="reviewer-info">
                                                        <div class="reviewer-avatar">
                                                            @if ($item->user->photo == '')
                                                            <img src="{{ asset('uploads/default.png') }}" alt="{{ $item->user->name }}">
                                                            @else
                                                            <img src="{{ asset('uploads/' . $item->user->photo) }}" alt="{{ $item->user->name }}">
                                                            @endif
                                                        </div>
                                                        <div class="reviewer-details">
                                                            <h4 class="reviewer-name">{{ $item->user->name }}</h4>
                                                            <span class="review-date">{{ $item->created_at->format('M. j, Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="review-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $item->rating)
                                                        <i class="fas fa-star"></i>
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    {!! $item->comment !!}
                                                </div>
                                            </div>
                                            @empty
                                            <div class="empty-state">
                                                <i class="fas fa-comment-slash"></i>
                                                <p>No reviews yet. Be the first to review this package!</p>
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-edit"></i></span>
                                            <h2 class="section-title">Leave Your Review</h2>
                                        </div>

                                        @if (Auth::guard('web')->check())
                                        @php
                                            $review_possible = App\Models\Booking::where('package_id', $package->id)
                                                ->where('user_id', Auth::guard('web')->user()->id)
                                                ->where('payment_status', 'Completed')
                                                ->count();
                                        @endphp

                                        @if ($review_possible > 0)
                                        @php
                                            App\Models\Review::where('package_id', $package->id)
                                                ->where('user_id', Auth::guard('web')->user()->id)
                                                ->count() > 0
                                                ? ($reviewed = true)
                                                : ($reviewed = false);
                                        @endphp

                                        @if ($reviewed == false)
                                        <form action="{{ route('review_submit') }}" method="post" class="review-form">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                                            <div class="form-group mb-4">
                                                <label class="form-label">Your Rating</label>
                                                <div class="star-rating">
                                                    <input type="radio" id="star5" name="rating" value="5" />
                                                    <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star4" name="rating" value="4" />
                                                    <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star3" name="rating" value="3" />
                                                    <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star2" name="rating" value="2" />
                                                    <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star1" name="rating" value="1" />
                                                    <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label class="form-label">Your Review</label>
                                                <textarea class="form-control elegant-textarea" rows="5" placeholder="Share your experience..." name="comment"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn-submit-review btn-elegant-cta">
                                                    <span>Submit Review</span>
                                                    <span class="btn-elegant-cta__icon">
                                                        <i class="fas fa-arrow-right btn-elegant-cta__arrow" aria-hidden="true"></i>
                                                        <i class="fas fa-plane btn-elegant-cta__plane" aria-hidden="true"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                        @else
                                        <div class="alert-message info">
                                            <i class="fas fa-info-circle"></i>
                                            <span>You have already submitted a review for this package.</span>
                                        </div>
                                        @endif
                                        @else
                                        <div class="alert-message warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>You need to book this package first to leave a review.</span>
                                        </div>
                                        @endif
                                        @else
                                        <div class="login-prompt">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <p>Please <a href="{{ route('login') }}">login</a> to leave a review.</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane fade" id="tab-7-pane" role="tabpanel" aria-labelledby="tab-7"
                                tabindex="0">
                                <div class="tab-content-wrapper">
                                    <div class="content-section">
                                        <div class="section-header">
                                            <span class="section-icon"><i class="fas fa-envelope"></i></span>
                                            <h2 class="section-title">Send Us an Enquiry</h2>
                                        </div>
                                        <div class="enquiry-form-wrapper">
                                            <form action="{{ route('enquery_form_submit', $package->id) }}" method="post" class="elegant-form">
                                                @csrf
                                                @php
                                                    $active_tours_enquiry = $tours->filter(fn($t) => $t->booking_end_date >= date('Y-m-d'));
                                                @endphp
                                                @if($active_tours_enquiry->count() > 0)
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Select Tour of Interest <span class="text-danger">*</span></label>
                                                    <p class="text-muted small mb-2">Choose the tour you're interested in (only one can be selected). This will be shared with our sales team.</p>
                                                    <input type="hidden" name="tour_id" value="{{ $active_tours_enquiry->first()->id }}" id="enquiry_tour_id_hidden">
                                                    <div class="tour-select-enquiry">
                                                        @foreach($active_tours_enquiry as $idx => $t)
                                                        <div class="tour-option mb-2 p-3 border rounded">
                                                            <div class="form-check">
                                                                <input class="form-check-input enquiry-tour-checkbox" type="checkbox" data-tour-id="{{ $t->id }}" id="enquiry_tour_{{ $t->id }}" {{ $idx === 0 ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="enquiry_tour_{{ $t->id }}">
                                                                    <strong>Tour {{ $idx + 1 }}</strong> — {{ \Carbon\Carbon::parse($t->tour_start_date)->format('M. j, Y') }} to {{ \Carbon\Carbon::parse($t->tour_end_date)->format('M. j, Y') }} <span class="text-muted">(Booking until {{ \Carbon\Carbon::parse($t->booking_end_date)->format('M. j, Y') }})</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text" class="form-control elegant-input" placeholder="Enter your full name" name="name" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="email" class="form-control elegant-input" placeholder="Enter your email" name="email" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control elegant-input" placeholder="Enter your phone number" name="phone" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Message</label>
                                                    <textarea class="form-control elegant-textarea" rows="5" placeholder="Tell us about your enquiry..." name="message" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn-submit-enquiry btn-elegant-cta">
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
                                </div>
                            </div>

                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    <style>
        /* Tours – elegantly compressed, global colors */
        .tours-compact-elegant { display: flex; flex-direction: column; gap: 6px; }
        .tours-compact-elegant .tour-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(158, 113, 2, 0.1);
            background: var(--white, #fdfdfd);
            transition: all 0.2s ease;
        }
        .tours-compact-elegant .tour-card:hover {
            border-color: rgba(158, 113, 2, 0.2);
            box-shadow: 0 2px 8px rgba(158, 113, 2, 0.06);
        }
        .tours-compact-elegant .tour-card-inner {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px 16px;
            padding: 10px 14px;
        }
        .tours-compact-elegant .tour-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .tours-compact-elegant .tour-checkbox input { accent-color: var(--primary); }
        .tours-compact-elegant .tour-checkbox .tour-number {
            font-weight: 600;
            color: var(--primary);
            font-size: 14px;
            letter-spacing: 0.02em;
        }
        .tours-compact-elegant .tour-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0 12px;
            font-size: 13px;
            color: #555;
            line-height: 1.4;
        }
        .tours-compact-elegant .tour-sep {
            color: rgba(158, 113, 2, 0.35);
            font-weight: 300;
        }
        .tours-compact-elegant .tour-dates { font-weight: 600; color: var(--black); }
        .tours-compact-elegant .tour-deadline { color: #666; }
        .tours-compact-elegant .tour-seats { color: #555; }
        .tours-compact-elegant .tour-sold-out .tour-card-inner { opacity: 0.6; }
        .tours-compact-elegant .tour-sold-out-badge {
            position: absolute;
            top: 8px;
            right: 12px;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }
        @media (max-width: 767px) {
            .tours-compact-elegant .tour-card-inner { padding: 10px 12px; gap: 8px; }
            .tours-compact-elegant .tour-meta { font-size: 12px; flex-direction: column; align-items: flex-start; gap: 2px; }
            .tours-compact-elegant .tour-sep { display: none; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enquiry form: single-select checkboxes, update hidden input
            document.querySelectorAll('.enquiry-tour-checkbox').forEach(function(cb) {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        document.querySelectorAll('.enquiry-tour-checkbox').forEach(function(o) { o.checked = false; });
                        this.checked = true;
                        document.getElementById('enquiry_tour_id_hidden').value = this.dataset.tourId;
                    }
                });
            });
            document.querySelectorAll('.enquiry-tour-checkbox').forEach(function(cb) {
                if (cb.checked) document.getElementById('enquiry_tour_id_hidden').value = cb.dataset.tourId;
            });
            // Booking form: single-select checkboxes (only one can be checked)
            document.querySelectorAll('.booking-tour-checkbox').forEach(function(cb) {
                cb.addEventListener('change', function() {
                    if (this.checked && !this.disabled) {
                        document.querySelectorAll('.booking-tour-checkbox').forEach(function(o) {
                            if (!o.disabled) o.checked = false;
                        });
                        this.checked = true;
                    }
                });
            });
        });
    </script>
@endsection
