<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    {{-- Sidebar Toggle --}}
    <ul class="navbar-nav mr-3">
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    {{-- Right Side Actions --}}
    <ul class="navbar-nav navbar-right justify-content-end rightsidetop">
        {{-- View Site Button --}}
        <li class="nav-item mr-3">
            <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-light shadow-sm">
                <i class="fas fa-external-link-alt mr-2"></i>
                <span class="d-none d-sm-inline">View Site</span>
            </a>
        </li>

        {{-- User Menu --}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" 
               href="#" 
               role="button" 
               data-bs-toggle="dropdown" 
               aria-expanded="false">
                @php
                    $avatarUrl = current_admin_user()->photo
                        ? asset('uploads/'.current_admin_user()->photo)
                        : 'data:image/svg+xml,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><circle fill="#6c757d" cx="16" cy="16" r="16"/><text fill="#fff" x="16" y="21" font-size="14" text-anchor="middle">'.strtoupper(substr(current_admin_user()->name ?? 'A', 0, 1)).'</text></svg>');
                @endphp
                <img alt="Profile" 
                     src="{{ $avatarUrl }}" 
                     class="rounded-circle-custom mr-2 user-avatar"
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2232%22 height=%2232%22 viewBox=%220 0 32 32%22%3E%3Ccircle fill=%22%236c757d%22 cx=%2216%22 cy=%2216%22 r=%2216%22/%3E%3Ctext fill=%22%23fff%22 x=%2216%22 y=%2221%22 font-size=%2214%22 text-anchor=%22middle%22%3EA%3C/text%3E%3C/svg%3E'">
                <span class="d-none d-md-inline font-weight-600">
                    {{ current_admin_user()->name ?? 'Admin' }}
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item py-2" href="{{ route('admin_profile') }}">
                        <i class="fas fa-user-edit mr-2 text-primary"></i>
                        Profile
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider my-1">
                </li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="{{ route('admin_logout') }}">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

{{-- Styles --}}
<style>
    /* User Avatar */
    .user-avatar {
        width: 32px;
        height: 32px;
    }
</style>
