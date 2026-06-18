<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\AboutItem;
use App\Models\Admin;
use App\Models\BlogCategory;
use App\Models\ContactItem;
use App\Models\ContactOffice;
use App\Models\CounterItem;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\PackageAmenity;
use App\Models\PackageFaq;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\PackageVideo;
use App\Models\Post;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\TermPrivacyItem;
use App\Models\Tour;
use App\Models\WelcomeItem;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function about()
    {
        $welcome = WelcomeItem::where('id', 1)->first();

        return response()->json([
            'welcome_item' => $welcome ? with_upload_urls($welcome, ['photo']) : null,
            'features' => Feature::get()->map(fn ($f) => with_upload_urls($f, ['photo'])),
            'counter_item' => CounterItem::where('id', 1)->first(),
            'about_item' => AboutItem::where('id', 1)->first(),
        ]);
    }

    public function contact()
    {
        return response()->json([
            'contact_item' => ContactItem::where('id', 1)->first(),
            'contact_offices' => ContactOffice::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'comment' => 'required',
        ]);

        Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?: null,
            'message' => $request->comment,
        ]);

        $admin = Admin::where('id', 1)->first();
        $subject = 'Contact Form Message';
        $message = '<b>Name:</b><br>'.$request->name.'<br><br>';
        $message .= '<b>Email:</b><br>'.$request->email.'<br><br>';
        if ($request->phone) {
            $message .= '<b>Phone:</b><br>'.$request->phone.'<br><br>';
        }
        $message .= '<b>Comment:</b><br>'.nl2br(e($request->comment)).'<br>';

        if ($admin?->email) {
            \Mail::to($admin->email)->send(new Websitemail($subject, $message));
        }

        return response()->json(['success' => true, 'message' => 'Your message is submitted successfully. We will contact you soon.']);
    }

    public function quickContact(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        $admin = Admin::where('id', 1)->first();
        $setting = Setting::where('id', 1)->first();
        $toEmail = ($admin && $admin->email) ? $admin->email : (($setting && $setting->top_bar_email) ? $setting->top_bar_email : config('mail.from.address'));

        if ($toEmail) {
            $subject = 'Quick Contact Request';
            $message = '<b>Name:</b><br>'.$request->name.'<br><br>';
            $message .= '<b>Email:</b><br>'.$request->email.'<br><br>';
            $message .= '<b>Phone:</b><br>'.$request->phone.'<br><br>';
            $message .= '<b>Message:</b><br>'.nl2br(e($request->message)).'<br>';
            \Mail::to($toEmail)->send(new Websitemail($subject, $message));
        }

        return response()->json(['success' => true, 'message' => 'Thank you! We will contact you soon.']);
    }

    public function faqs()
    {
        return response()->json(['faqs' => Faq::get()]);
    }

    public function terms()
    {
        return response()->json(['term_privacy_item' => TermPrivacyItem::where('id', 1)->first()]);
    }

    public function privacy()
    {
        return response()->json(['term_privacy_item' => TermPrivacyItem::where('id', 1)->first()]);
    }

    public function teamMembers()
    {
        $team = TeamMember::paginate(20);
        $team->getCollection()->transform(fn ($m) => with_upload_urls($m, ['photo']));

        return response()->json($team);
    }

    public function teamMember(string $slug)
    {
        $member = TeamMember::where('slug', $slug)->firstOrFail();

        return response()->json(['data' => with_upload_urls($member, ['photo'])]);
    }

    public function blog()
    {
        $posts = Post::with('blog_category')->orderBy('id', 'desc')->paginate(9);
        $posts->getCollection()->transform(fn ($p) => with_upload_urls($p, ['photo']));

        return response()->json($posts);
    }

    public function post(string $slug)
    {
        $post = Post::with('blog_category')->where('slug', $slug)->firstOrFail();
        $categories = BlogCategory::orderBy('name', 'asc')->get();
        $latest = Post::with('blog_category')->orderBy('id', 'desc')->take(5)->get()
            ->map(fn ($p) => with_upload_urls($p, ['photo']));

        return response()->json([
            'post' => with_upload_urls($post, ['photo']),
            'categories' => $categories,
            'latest_posts' => $latest,
        ]);
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();
        $posts = Post::with('blog_category')->where('blog_category_id', $category->id)
            ->orderBy('id', 'desc')->paginate(9);
        $posts->getCollection()->transform(fn ($p) => with_upload_urls($p, ['photo']));

        return response()->json(['category' => $category, 'posts' => $posts]);
    }

    public function destinations()
    {
        $destinations = Destination::orderBy('id', 'asc')->paginate(20);
        $destinations->getCollection()->transform(fn ($d) => with_upload_urls($d, ['featured_photo']));

        return response()->json($destinations);
    }

    public function destination(string $slug)
    {
        $destination = Destination::where('slug', $slug)->firstOrFail();
        $destination->view_count = $destination->view_count + 1;
        $destination->update();

        $photos = DestinationPhoto::where('destination_id', $destination->id)->get()
            ->map(fn ($p) => with_upload_urls($p, ['photo']));
        $videos = DestinationVideo::where('destination_id', $destination->id)->get();
        $packages = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
            ->orderBy('id', 'desc')->where('destination_id', $destination->id)->take(3)->get()
            ->map(fn ($p) => with_upload_urls($p, ['featured_photo']));

        return response()->json([
            'destination' => with_upload_urls($destination, ['featured_photo']),
            'photos' => $photos,
            'videos' => $videos,
            'packages' => $packages,
        ]);
    }

    public function packages(Request $request)
    {
        $destinations = Destination::orderBy('name', 'asc')->get()
            ->map(fn ($d) => with_upload_urls($d, ['featured_photo']));

        $query = Package::with(['destination', 'package_amenities', 'package_itineraries', 'tours', 'reviews'])
            ->orderBy('id', 'desc');

        if ($request->name) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->destination_id) {
            $query->where('destination_id', $request->destination_id);
        }
        if ($request->review && $request->review !== 'all') {
            $query->whereRaw('total_score/total_rating = ?', [$request->review]);
        }

        $packages = $query->paginate(6);
        $packages->getCollection()->transform(fn ($p) => with_upload_urls($p, ['featured_photo']));

        return response()->json([
            'destinations' => $destinations,
            'packages' => $packages,
            'filters' => [
                'name' => $request->name,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'destination_id' => $request->destination_id,
                'review' => $request->review,
            ],
        ]);
    }

    public function package(string $slug)
    {
        $package = Package::with('destination')->where('slug', $slug)->firstOrFail();

        return response()->json([
            'package' => with_upload_urls($package, ['featured_photo', 'banner']),
            'amenities_include' => PackageAmenity::with('amenity')->where('package_id', $package->id)->where('type', 'Include')->get(),
            'amenities_exclude' => PackageAmenity::with('amenity')->where('package_id', $package->id)->where('type', 'Exclude')->get(),
            'itineraries' => PackageItinerary::where('package_id', $package->id)->get(),
            'photos' => PackagePhoto::where('package_id', $package->id)->get()->map(fn ($p) => with_upload_urls($p, ['photo'])),
            'videos' => PackageVideo::where('package_id', $package->id)->get(),
            'faqs' => PackageFaq::where('package_id', $package->id)->get(),
            'tours' => Tour::where('package_id', $package->id)->get(),
            'reviews' => Review::with('user')->where('package_id', $package->id)->where('status', 'Approved')->get(),
        ]);
    }

    public function subscriberSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
        ]);

        $token = hash('sha256', time());
        Subscriber::create([
            'email' => $request->email,
            'token' => $token,
            'status' => 'Pending',
        ]);

        $verificationLink = url('/subscriber_verify/'.$request->email.'/'.$token);
        $subject = 'Subscriber Verification';
        $message = 'Please click the following link to verify your email address as subscriber:<br><a href="'.$verificationLink.'">Verify Email</a>';
        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return response()->json(['success' => true, 'message' => 'You are subscribed successfully. Please check your email to confirm the verification link.']);
    }

    public function enquirySubmit(Request $request, int $id)
    {
        $package = Package::where('id', $id)->firstOrFail();
        $admin = Admin::where('id', 1)->first();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
        ]);

        $subject = 'Enquiry about: '.$package->name;
        $message = '<b>Name:</b><br>'.$request->name.'<br><br>';
        $message .= '<b>Email:</b><br>'.$request->email.'<br><br>';
        $message .= '<b>Phone:</b><br>'.$request->phone.'<br><br>';
        if ($request->tour_id) {
            $tour = Tour::where('id', $request->tour_id)->where('package_id', $package->id)->first();
            if ($tour) {
                $message .= '<b>Selected Tour:</b><br>'.$tour->tour_start_date.' to '.$tour->tour_end_date.' (Booking until '.$tour->booking_end_date.')<br><br>';
            }
        }
        $message .= '<b>Message:</b><br>'.nl2br($request->message).'<br>';

        if ($admin?->email) {
            \Mail::to($admin->email)->send(new Websitemail($subject, $message));
        }

        return response()->json(['success' => true, 'message' => 'Your enquiry is submitted successfully. We will contact you soon.']);
    }
}
