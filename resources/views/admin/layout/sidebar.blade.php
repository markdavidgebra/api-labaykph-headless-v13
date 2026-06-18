<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin_dashboard') }}">{{ is_super_admin() ? 'Dev Panel' : 'Admin Panel' }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin_dashboard') }}"></a>
        </div>

        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- Travel Management --}}
            <li class="menu-header">Travel</li>

            <li class="{{ Request::is('admin/destination') || Request::is('admin/destination/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_destination_index') }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Destination</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/package') || Request::is('admin/package/*') || Request::is('admin/package-itineraries/*') || Request::is('admin/package-itinerary-*') || Request::is('admin/package-amenities/*') || Request::is('admin/package-amenity-*') || Request::is('admin/package-photos/*') || Request::is('admin/package-photo-*') || Request::is('admin/package-videos/*') || Request::is('admin/package-video-*') || Request::is('admin/package-faqs/*') || Request::is('admin/package-faq-*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_package_index') }}">
                    <i class="fas fa-suitcase"></i>
                    <span>Package</span>
                </a>
            </li>

            @php
                $sidebar_new_bookings_count = \App\Models\Booking::whereNull('admin_viewed_at')->count();
            @endphp
            <li class="{{ Request::is('admin/tour') || Request::is('admin/tour/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_tour_index') }}">
                    <i class="fas fa-route"></i>
                    @if($sidebar_new_bookings_count > 0)
                        <span class="badge badge-danger badge-sm tour-booking-badge">{{ $sidebar_new_bookings_count > 99 ? '99+' : $sidebar_new_bookings_count }}</span>
                    @endif
                    <span>Tour</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/amenity') || Request::is('admin/amenity/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_amenity_index') }}">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Amenity</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/inquiry') || Request::is('admin/inquiry/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_inquiry_index') }}">
                    <i class="fas fa-inbox"></i>
                    <span>Inquiries</span>
                </a>
            </li>

            @if(is_super_admin())
            {{-- Content Management (Super Admin only) --}}
            <li class="menu-header">Content</li>
            
            <li class="{{ Request::is('admin/slider') || Request::is('admin/slider/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_slider_index') }}">
                    <i class="fas fa-images"></i>
                    <span>Slider</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/welcome-item') || Request::is('admin/welcome-item/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_welcome_item_index') }}">
                    <i class="fas fa-hand-sparkles"></i>
                    <span>Welcome Item</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/feature') || Request::is('admin/feature/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_feature_index') }}">
                    <i class="fas fa-star"></i>
                    <span>Feature</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/counter-item') || Request::is('admin/counter-item/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_counter_item_index') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Counter Item</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/testimonial') || Request::is('admin/testimonial/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_testimonial_index') }}">
                    <i class="fas fa-quote-left"></i>
                    <span>Testimonial</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/home-item') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_home_item_index') }}#cta-journey">
                    <i class="fas fa-road"></i>
                    <span>CTA Journey</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/team-member') || Request::is('admin/team-member/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_team_member_index') }}">
                    <i class="fas fa-users"></i>
                    <span>Team Member</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/faq') || Request::is('admin/faq/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_faq_index') }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
            </li>

            {{-- Blog Section --}}
            <li class="nav-item dropdown {{ Request::is('admin/blog-category') || Request::is('admin/blog-category/*') || Request::is('admin/post') || Request::is('admin/post/*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-blog"></i>
                    <span>Blog</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/blog-category') || Request::is('admin/blog-category/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_blog_category_index') }}">
                            <i class="fas fa-folder"></i>
                            <span>Category</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/post') || Request::is('admin/post/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_post_index') }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Post</span>
                        </a>
                    </li>
                </ul>
            </li>

            @else
            {{-- Admin: Testimonial, Team Member, Contact Page, Terms & Privacy --}}
            <li class="menu-header">Content</li>
            <li class="{{ Request::is('admin/testimonial') || Request::is('admin/testimonial/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_testimonial_index') }}">
                    <i class="fas fa-quote-left"></i>
                    <span>Testimonial</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/team-member') || Request::is('admin/team-member/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_team_member_index') }}">
                    <i class="fas fa-users"></i>
                    <span>Team Member</span>
                </a>
            </li>
            <li class="menu-header">Pages</li>
            <li class="{{ Request::is('admin/contact-item') || Request::is('admin/contact-item/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_contact_item_index') }}">
                    <i class="fas fa-address-book"></i>
                    <span>Contact Page</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/term-privacy-item') || Request::is('admin/term-privacy-item/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_term_privacy_item_index') }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Terms & Privacy</span>
                </a>
            </li>
            @endif

            {{-- User Management --}}
            <li class="menu-header">Users</li>

            @php
                // Count messages needing admin attention (unviewed or with new customer replies)
                $sidebar_new_messages_count = 0;
                $sidebar_all_messages = \App\Models\Message::with(['comments', 'user'])->get();
                foreach($sidebar_all_messages as $msg) {
                    $should_notify = false;
                    if(is_null($msg->admin_viewed_at)) {
                        $should_notify = true;
                    } else {
                        $new_user_comments = $msg->comments->filter(function($c) use ($msg) {
                            return $c->type == 'User' && $c->created_at > $msg->admin_viewed_at;
                        });
                        if($new_user_comments->count() > 0) $should_notify = true;
                    }
                    if($should_notify) $sidebar_new_messages_count++;
                }
                // Count new (unviewed) users
                $sidebar_new_users_count = \App\Models\User::where('status', 1)->whereNull('admin_viewed_at')->count();
                // Count unviewed subscribers (notification clears when admin visits the page)
                $sidebar_new_subscribers_count = \App\Models\Subscriber::whereNull('admin_viewed_at')->count();
                // Count new reviews (pending approval or unviewed)
                $sidebar_new_reviews_count = \App\Models\Review::where(function($q) {
                    $q->where('status', 'Pending')->orWhereNull('admin_viewed_at');
                })->count();
                // Combined badge for Users parent when dropdown closed
                $sidebar_users_parent_badge = $sidebar_new_messages_count + $sidebar_new_users_count;
            @endphp

            <li class="nav-item dropdown users-dropdown {{ Request::is('admin/message') || Request::is('admin/users') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-user-friends"></i>
                    @if($sidebar_users_parent_badge > 0)
                        <span class="badge badge-danger badge-sm users-messages-badge-parent">{{ $sidebar_users_parent_badge > 99 ? '99+' : $sidebar_users_parent_badge }}</span>
                    @endif
                    <span>Users</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_users') }}">
                            <i class="fas fa-users-cog"></i>
                            @if($sidebar_new_users_count > 0)
                                <span class="badge badge-danger badge-sm users-new-badge">{{ $sidebar_new_users_count > 99 ? '99+' : $sidebar_new_users_count }}</span>
                            @endif
                            <span>All Users</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/message') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_message') }}">
                            <i class="fas fa-envelope"></i>
                            @if($sidebar_new_messages_count > 0)
                                <span class="badge badge-danger badge-sm users-messages-badge-child">{{ $sidebar_new_messages_count > 99 ? '99+' : $sidebar_new_messages_count }}</span>
                            @endif
                            <span>Messages</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown subscribers-dropdown {{ Request::is('admin/subscribers') || Request::is('admin/subscriber-send-email') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown">
                    <i class="fas fa-bell"></i>
                    @if($sidebar_new_subscribers_count > 0)
                        <span class="badge badge-danger badge-sm subscribers-new-badge-parent">{{ $sidebar_new_subscribers_count > 99 ? '99+' : $sidebar_new_subscribers_count }}</span>
                    @endif
                    <span>Subscribers</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/subscribers') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_subscribers') }}">
                            <i class="fas fa-list"></i>
                            @if($sidebar_new_subscribers_count > 0)
                                <span class="badge badge-danger badge-sm subscribers-new-badge-child">{{ $sidebar_new_subscribers_count > 99 ? '99+' : $sidebar_new_subscribers_count }}</span>
                            @endif
                            <span>All Subscribers</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/subscriber-send-email') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin_subscriber_send_email') }}">
                            <i class="fas fa-paper-plane"></i>
                            <span>Send Email</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('admin/review') || Request::is('admin/review/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_review_index') }}">
                    <i class="fas fa-star-half-alt"></i>
                    @if($sidebar_new_reviews_count > 0)
                        <span class="badge badge-danger badge-sm reviews-new-badge">{{ $sidebar_new_reviews_count > 99 ? '99+' : $sidebar_new_reviews_count }}</span>
                    @endif
                    <span>Reviews</span>
                </a>
            </li>

            @if(is_super_admin())
            {{-- Page Settings (Super Admin only) --}}
            <li class="menu-header">Pages</li>

            <li class="{{ Request::is('admin/home-item') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_home_item_index') }}">
                    <i class="fas fa-home"></i>
                    <span>Home Page</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/about-item') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_about_item_index') }}">
                    <i class="fas fa-info-circle"></i>
                    <span>About Page</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/contact-item') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_contact_item_index') }}">
                    <i class="fas fa-address-book"></i>
                    <span>Contact Page</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/term-privacy-item') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_term_privacy_item_index') }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Terms & Privacy</span>
                </a>
            </li>

            @endif

            {{-- Settings & Profile --}}
            <li class="menu-header">System</li>

            <li class="{{ Request::is('admin/setting') || Request::is('admin/setting/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_setting_index') }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin_profile') }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </aside>
</div>

{{-- Active menu styling: bold, distinct color, background --}}
<style>
.main-sidebar .sidebar-menu li.active > a,
.main-sidebar .sidebar-menu li.active a.nav-link,
.main-sidebar .sidebar-menu li ul.dropdown-menu li.active > a,
.main-sidebar .sidebar-menu li.nav-item.dropdown.active > a {
    font-weight: 700 !important;
    color: #9e7102 !important;
    background-color: rgba(158, 113, 2, 0.12) !important;
    border-left: 3px solid #9e7102;
}
.main-sidebar .sidebar-menu li.active ul.dropdown-menu li.active > a {
    font-weight: 700 !important;
    color: #9e7102 !important;
    background-color: rgba(158, 113, 2, 0.08) !important;
    border-left: 3px solid #9e7102;
}
/* Inactive dropdown children: white background */
.main-sidebar .sidebar-menu li ul.dropdown-menu li:not(.active) > a {
    background-color: #fff !important;
}
</style>