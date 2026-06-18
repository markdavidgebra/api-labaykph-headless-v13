                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <ul class="list-group list-group-flush">
    <li class="list-group-item {{ Route::is('user_dashboard') ? 'active' : '' }}">
        <a href="{{ route('user_dashboard') }}">Dashboard</a>
    </li>
    <li class="list-group-item {{ Route::is('user_booking')||Request::is('user/invoice/*') ? 'active' : '' }}">
        <a href="{{ route('user_booking') }}">Booking</a>
    </li>
    <li class="list-group-item {{ Route::is('user_wishlist') ? 'active' : '' }}">
        <a href="{{ route('user_wishlist') }}">Wishlist</a>
    </li>
    <li class="list-group-item {{ Route::is('user_message') ? 'active' : '' }}">
        <a href="{{ route('user_message') }}" class="position-relative">
            Message
            @php
                $admin_comments_count = 0;
                if(Auth::guard('web')->check()) {
                    $user_message = \App\Models\Message::where('user_id', Auth::guard('web')->user()->id)->first();
                    if($user_message) {
                        $query = \App\Models\MessageComment::where('message_id', $user_message->id)
                            ->where('type', 'Admin');
                        if($user_message->user_viewed_at) {
                            $query->where('created_at', '>', $user_message->user_viewed_at);
                        }
                        $admin_comments_count = $query->count();
                    }
                }
            @endphp
            <span id="sidebarMessageBadge" class="badge badge-danger badge-sm user-message-badge" style="{{ $admin_comments_count > 0 ? '' : 'display:none;' }}">{{ $admin_comments_count > 99 ? '99+' : $admin_comments_count }}</span>
        </a>
    </li>
    <li class="list-group-item {{ Route::is('user_review') ? 'active' : '' }}">
        <a href="{{ route('user_review') }}">Reviews</a>
    </li>
    <li class="list-group-item {{ Route::is('user_profile') ? 'active' : '' }}">
        <a href="{{ route('user_profile') }}">Edit Profile</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('logout') }}">Logout</a>
    </li>
</ul>