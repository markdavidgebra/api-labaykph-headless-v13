@extends('admin.layout.master')
@section('main_content')
@include('admin.layout.nav')
@include('admin.layout.sidebar')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        {{-- Recent Activity Section --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="section-header">
                    <h4>Recent Activity</h4>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">Latest Updates</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Recent Bookings --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-calendar-check mr-2 text-primary"></i>
                            Recent Bookings
                            @if($unviewed_bookings_count > 0)
                                <span class="badge badge-danger badge-sm ml-2">{{ $unviewed_bookings_count }}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_bookings_list->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        @foreach($recent_bookings_list as $booking)
                                            <tr style="cursor: pointer;" onclick="markBookingViewed({{ $booking->id }}); window.location.href='{{ route('admin_tour_booking', ['tour_id' => $booking->tour_id, 'package_id' => $booking->package_id]) }}'" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''" class="{{ is_null($booking->admin_viewed_at) ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-calendar-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ml-3">
                                                            <div class="font-weight-bold">
                                                                {{ $booking->user->name ?? 'Guest' }} - 
                                                                {{ $booking->package->name ?? 'Package N/A' }}
                                                                @if($booking->tour)
                                                                    <span class="text-muted">(Tour)</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-small text-muted">
                                                                <i class="fas fa-users"></i> {{ $booking->total_person }} person(s)
                                                                <span class="mx-2">•</span>
                                                                <i class="fas fa-dollar-sign"></i> {{ $booking->paid_amount ?? '0' }}
                                                                <span class="mx-2">•</span>
                                                                <span class="badge badge-{{ $booking->payment_status == 'Completed' ? 'success' : 'warning' }}">
                                                                    {{ $booking->payment_status ?? 'Pending' }}
                                                                </span>
                                                                <span class="mx-2">•</span>
                                                                <i class="far fa-clock"></i> {{ $booking->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent bookings</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Users --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-user-friends mr-2 text-danger"></i>
                            Recent Users
                            @if($unviewed_users_count > 0)
                                <span class="badge badge-danger badge-sm ml-2">{{ $unviewed_users_count }}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_users_list->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        @foreach($recent_users_list as $user)
                                            <tr style="cursor: pointer;" onclick="markUserViewed({{ $user->id }}); window.location.href='{{ route('admin_user_edit', $user->id) }}'" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''" class="{{ is_null($user->admin_viewed_at) ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ml-3">
                                                            <div class="font-weight-bold">{{ $user->name }}</div>
                                                            <div class="text-small text-muted">
                                                                <i class="far fa-envelope"></i> {{ Str::limit($user->email, 30) }}
                                                                <span class="mx-2">•</span>
                                                                <i class="far fa-clock"></i> {{ $user->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent users</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages & Reviews --}}
        <div class="row mt-4">
            {{-- Recent Messages --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-envelope mr-2 text-info"></i>
                            Recent Messages
                            @if($unviewed_messages_count > 0)
                                <span class="badge badge-danger badge-sm ml-2">{{ $unviewed_messages_count }}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_messages->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        @foreach($recent_messages as $message)
                                            @php
                                                $message_has_new = is_null($message->admin_viewed_at);
                                                if(!$message_has_new && $message->comments) {
                                                    $new_user_comments = $message->comments->filter(function($comment) use ($message) {
                                                        return $comment->type == 'User' && $comment->created_at > $message->admin_viewed_at;
                                                    });
                                                    $message_has_new = $new_user_comments->count() > 0;
                                                }
                                            @endphp
                                            <tr style="cursor: pointer;" onclick="window.location.href='{{ route('admin_message_detail', $message->id) }}'" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''" class="{{ $message_has_new ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-envelope"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ml-3">
                                                            <div class="font-weight-bold">Message from {{ $message->user->name ?? 'Guest' }}</div>
                                                            <div class="text-small text-muted">
                                                                <i class="far fa-clock"></i> {{ $message->created_at->setTimezone('Asia/Manila')->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent messages</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Reviews --}}
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-star mr-2 text-warning"></i>
                            Recent Reviews
                            @if($unviewed_reviews_count > 0)
                                <span class="badge badge-danger badge-sm ml-2">{{ $unviewed_reviews_count }}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        @if($recent_reviews->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        @foreach($recent_reviews as $review)
                                            @php
                                                $review_is_unviewed = is_null($review->admin_viewed_at) || $review->status == 'Pending';
                                            @endphp
                                            <tr style="cursor: pointer;" onclick="markReviewViewed({{ $review->id }}); window.location.href='{{ route('admin_review_index') }}'" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor=''" class="{{ $review_is_unviewed ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avatar bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-star"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 ml-3">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="font-weight-bold mr-2">{{ $review->user->name ?? 'Guest' }}</span>
                                                                <span class="text-warning">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <i class="fas fa-star{{ $i <= ($review->rating ?? 0) ? '' : '-o' }}"></i>
                                                                    @endfor
                                                                </span>
                                                                @if($review->status == 'Pending')
                                                                    <span class="badge badge-warning badge-sm ml-2">Pending</span>
                                                                @endif
                                                            </div>
                                                            <div class="text-small text-muted">
                                                                {{ Str::limit($review->comment ?? 'No review text', 40) }}
                                                                <span class="mx-2">•</span>
                                                                <i class="far fa-clock"></i> {{ $review->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent reviews</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row">
            {{-- Sliders --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Sliders</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_slider }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Testimonials --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Testimonials</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_testimonial }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Team Members --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Team Members</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_team_members }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Posts --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Posts</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_posts }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Destinations --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Destinations</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_destinations }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Packages --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-suitcase"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Packages</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_packages }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tours --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Tours</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_tours }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Users</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_users }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscribers --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Subscribers</h4>
                        </div>
                        <div class="card-body">
                            {{ $total_subscribers }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Analytics Section --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="section-header">
                    <h4>Analytics Overview</h4>
                    <div class="section-header-breadcrumb">
                        <div class="breadcrumb-item">Performance Metrics</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Bookings Growth --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <div class="d-flex justify-content-between">
                                <div>Booking of this Month</div>
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-stats-items">
                            <div class="card-stats-item">
                                <div class="card-stats-item-count">{{ $this_month_bookings }}</div>
                                <div class="card-stats-item-label">This Month</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Growth Rate</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold {{ $bookings_growth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $bookings_growth >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($bookings_growth) }}%
                                </span>
                                <small class="text-muted">vs last month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users Growth --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <div class="d-flex justify-content-between">
                                <div>New Users</div>
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-stats-items">
                            <div class="card-stats-item">
                                <div class="card-stats-item-count">{{ $this_month_users }}</div>
                                <div class="card-stats-item-label">This Month</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-danger bg-danger">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Growth Rate</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold {{ $users_growth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $users_growth >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($users_growth) }}%
                                </span>
                                <small class="text-muted">vs last month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscribers Growth --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <div class="d-flex justify-content-between">
                                <div>Subscribers</div>
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-stats-items">
                            <div class="card-stats-item">
                                <div class="card-stats-item-count">{{ $this_month_subscribers }}</div>
                                <div class="card-stats-item-label">This Month</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-success bg-success">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Growth Rate</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold {{ $subscribers_growth >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $subscribers_growth >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($subscribers_growth) }}%
                                </span>
                                <small class="text-muted">vs last month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Summary --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-stats">
                        <div class="card-stats-title">
                            <div class="d-flex justify-content-between">
                                <div>30-Day Activity</div>
                                <div class="dropdown">
                                    <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-stats-items">
                            <div class="card-stats-item">
                                <div class="card-stats-item-count">{{ $recent_posts + $recent_users + $recent_subscribers + $recent_packages }}</div>
                                <div class="card-stats-item-label">Total New Items</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-icon shadow-warning bg-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Breakdown</h4>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 border-right">
                                    <div class="text-small text-muted mb-1">Posts</div>
                                    <div class="font-weight-bold">{{ $recent_posts }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-small text-muted mb-1">Users</div>
                                    <div class="font-weight-bold">{{ $recent_users }}</div>
                                </div>
                                <div class="col-6 border-right border-top pt-2 mt-2">
                                    <div class="text-small text-muted mb-1">Subscribers</div>
                                    <div class="font-weight-bold">{{ $recent_subscribers }}</div>
                                </div>
                                <div class="col-6 border-top pt-2 mt-2">
                                    <div class="text-small text-muted mb-1">Packages</div>
                                    <div class="font-weight-bold">{{ $recent_packages }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
</div>

<script>
    function markBookingViewed(bookingId) {
        fetch('{{ route("admin_dashboard_mark_booking_viewed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ booking_id: bookingId })
        }).catch(err => console.error('Error marking booking as viewed:', err));
    }

    function markUserViewed(userId) {
        fetch('{{ route("admin_dashboard_mark_user_viewed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ user_id: userId })
        }).catch(err => console.error('Error marking user as viewed:', err));
    }

    function markReviewViewed(reviewId) {
        fetch('{{ route("admin_dashboard_mark_review_viewed") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ review_id: reviewId })
        }).catch(err => console.error('Error marking review as viewed:', err));
    }
</script>
@endsection