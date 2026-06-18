<div class="navbar-area" id="stickymenu">
    <!-- Menu For Mobile Device -->
    <div class="mobile-nav">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('uploads/'.$setting->logo) }}" alt="">
        </a>
    </div>

<!-- Menu For Desktop Device -->
    <div class="main-nav nav-elegant">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('uploads/'.$setting->logo) }}" alt="">
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto nav-elegant-menu">
                        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item {{ Route::is('about') ? 'active' : '' }}">
                            <a href="{{ route('about') }}" class="nav-link">About</a>
                        </li>
                        <li class="nav-item {{ Route::is('destinations') || Route::is('destination') ? 'active' : '' }}">
                            <a href="{{ route('destinations') }}" class="nav-link">Destinations</a>
                        </li>
                        <li class="nav-item {{ Route::is('packages') || Route::is('package') ? 'active' : '' }}">
                            <a href="{{ route('packages') }}" class="nav-link">Packages</a>
                        </li>
                        <li class="nav-item {{ Route::is('team_members') || Route::is('team_member') ? 'active' : '' }}">
                            <a href="{{ route('team_members') }}" class="nav-link">Team</a>
                        </li>
                        <li class="nav-item {{ Route::is('faq') ? 'active' : '' }}">
                            <a href="{{ route('faq') }}" class="nav-link">FAQs</a>
                        </li>
                        <li class="nav-item {{ Route::is('blog') || Route::is('post') || Route::is('category') ? 'active' : '' }}">
                            <a href="{{ route('blog') }}" class="nav-link">Latest News</a>
                        </li>
                        <li class="nav-item {{ Route::is('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>    
</div>