<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminAccess
{
    /**
     * Admin (non-superadmin) can only access: Dashboard, Travel, Users, System.
     * Super admin can access everything.
     */
    protected array $adminAllowedPaths = [
        'admin/dashboard',
        'admin/destination',
        'admin/package',
        'admin/package-itineraries',
        'admin/package-itinerary-',
        'admin/package-amenities',
        'admin/package-amenity-',
        'admin/package-photos',
        'admin/package-photo-',
        'admin/package-videos',
        'admin/package-video-',
        'admin/package-faqs',
        'admin/package-faq-',
        'admin/amenity',
        'admin/tour',
        'admin/users',
        'admin/user/',
        'admin/message',
        'admin/subscribers',
        'admin/subscriber-',
        'admin/review',
        'admin/testimonial',
        'admin/team-member',
        'admin/contact-item',
        'admin/inquiry',
        'admin/term-privacy-item',
        'admin/setting',
        'admin/profile',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Super admin can access everything
        if (Auth::guard('superadmin')->check()) {
            return $next($request);
        }

        // Admin must be restricted
        if (Auth::guard('admin')->check()) {
            $path = $request->path();

            foreach ($this->adminAllowedPaths as $allowedPath) {
                if (str_starts_with($path, $allowedPath)) {
                    return $next($request);
                }
            }

            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
