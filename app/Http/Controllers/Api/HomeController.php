<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Feature;
use App\Models\HomeItem;
use App\Models\Package;
use App\Models\Post;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\WelcomeItem;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::get()->map(fn ($s) => with_upload_urls($s, ['photo']));
        $welcome = WelcomeItem::where('id', 1)->first();
        $features = Feature::get()->map(fn ($f) => with_upload_urls($f, ['photo']));
        $testimonials = Testimonial::get()->map(fn ($t) => with_upload_urls($t, ['photo']));
        $destinations = Destination::orderBy('view_count', 'desc')->take(8)->get()
            ->map(fn ($d) => with_upload_urls($d, ['featured_photo']));
        $posts = Post::with('blog_category')->orderBy('id', 'desc')->take(3)->get()
            ->map(fn ($p) => with_upload_urls($p, ['photo']));
        $packages = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
            ->orderBy('id', 'desc')->take(3)->get()
            ->map(fn ($p) => with_upload_urls($p, ['featured_photo']));
        $homeItem = HomeItem::where('id', 1)->first();
        $homeItemData = $homeItem
            ? with_upload_urls($homeItem, ['testimonial_background', 'cta_background'])
            : null;
        $welcomeData = $welcome ? with_upload_urls($welcome, ['photo']) : null;

        return response()->json([
            'sliders' => $sliders,
            'welcome_item' => $welcomeData,
            'features' => $features,
            'testimonials' => $testimonials,
            'destinations' => $destinations,
            'posts' => $posts,
            'packages' => $packages,
            'home_item' => $homeItemData,
        ]);
    }
}
