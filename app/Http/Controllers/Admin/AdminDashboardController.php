<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\TeamMember;
use App\Models\Post;
use App\Models\Destination;
use App\Models\Package;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\Tour;
use App\Models\Message;
use App\Models\Review;
use App\Models\Booking;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Total counts
        $total_slider = Slider::count();
        $total_testimonial = Testimonial::count();
        $total_team_members = TeamMember::count();
        $total_posts = Post::count();
        $total_destinations = Destination::count();
        $total_packages = Package::count();
        $total_users = User::where('status',1)->count();
        $total_subscribers = Subscriber::where('status','Active')->count();
        $total_tours = Tour::count();

        // Analytics - Recent Activity (Last 30 days)
        $recent_posts = Post::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recent_users = User::where('status', 1)->where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recent_subscribers = Subscriber::where('status', 'Active')->where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recent_packages = Package::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Analytics - This Month vs Last Month
        $this_month_bookings = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        $last_month_bookings = Booking::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $bookings_growth = $last_month_bookings > 0 ? round((($this_month_bookings - $last_month_bookings) / $last_month_bookings) * 100, 1) : 0;

        $this_month_users = User::where('status', 1)->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        $last_month_users = User::where('status', 1)->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $users_growth = $last_month_users > 0 ? round((($this_month_users - $last_month_users) / $last_month_users) * 100, 1) : 0;

        $this_month_subscribers = Subscriber::where('status', 'Active')->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        $last_month_subscribers = Subscriber::where('status', 'Active')->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();
        $subscribers_growth = $last_month_subscribers > 0 ? round((($this_month_subscribers - $last_month_subscribers) / $last_month_subscribers) * 100, 1) : 0;

        // Recent Items
        $recent_bookings_list = Booking::with(['user', 'package', 'tour'])->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_users_list = User::where('status', 1)->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_messages = Message::with('user')->orderBy('created_at', 'desc')->limit(5)->get();
        $recent_reviews = Review::with(['user', 'package'])->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Count unviewed items for notifications
        $unviewed_bookings_count = Booking::whereNull('admin_viewed_at')->count();
        $unviewed_users_count = User::where('status', 1)->whereNull('admin_viewed_at')->count();
        
        // Count unviewed messages (not viewed or with new user comments)
        $all_messages_for_count = Message::with([
            'comments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();
        $unviewed_messages_count = 0;
        foreach($all_messages_for_count as $msg) {
            if(is_null($msg->admin_viewed_at)) {
                $unviewed_messages_count++;
            } else {
                $new_user_comments = $msg->comments->filter(function($comment) use ($msg) {
                    return $comment->type == 'User' && $comment->created_at > $msg->admin_viewed_at;
                });
                if($new_user_comments->count() > 0) {
                    $unviewed_messages_count++;
                }
            }
        }
        
        // Count unviewed reviews (pending approval or not viewed)
        $unviewed_reviews_count = Review::where(function($query) {
            $query->where('status', 'Pending')
                  ->orWhereNull('admin_viewed_at');
        })->count();

        return view('admin.dashboard', compact(
            'total_slider', 'total_testimonial', 'total_team_members', 'total_posts', 
            'total_destinations', 'total_packages', 'total_users', 'total_subscribers', 'total_tours',
            'recent_posts', 'recent_users', 'recent_subscribers', 'recent_packages',
            'this_month_bookings', 'this_month_users', 'this_month_subscribers',
            'bookings_growth', 'users_growth', 'subscribers_growth',
            'recent_bookings_list', 'recent_users_list', 'recent_messages', 'recent_reviews',
            'unviewed_bookings_count', 'unviewed_users_count', 'unviewed_messages_count', 'unviewed_reviews_count'
        ));
    }

    public function markBookingViewed(Request $request)
    {
        $booking = Booking::where('id', $request->booking_id)->first();
        if($booking) {
            $booking->admin_viewed_at = now();
            $booking->save();
        }
        return response()->json(['success' => true]);
    }

    public function markUserViewed(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if($user) {
            $user->admin_viewed_at = now();
            $user->save();
        }
        return response()->json(['success' => true]);
    }

    public function markReviewViewed(Request $request)
    {
        $review = Review::where('id', $request->review_id)->first();
        if($review) {
            $review->admin_viewed_at = now();
            $review->save();
        }
        return response()->json(['success' => true]);
    }

    /**
     * Real-time notifications poll - returns counts and lists for nav badges/dropdowns.
     */
    public function notificationsPoll(Request $request)
    {
        $bookings = [];
        $bookings_count = 0;
        $messages = [];
        $messages_count = 0;
        $reviews_count = 0;

        try {
            if (\Schema::hasColumn('bookings', 'admin_viewed_at')) {
                $new_bookings = Booking::whereNull('admin_viewed_at')
                    ->with(['user', 'package', 'tour'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
                $bookings_count = Booking::whereNull('admin_viewed_at')->count();
                foreach ($new_bookings as $b) {
                    $bookings[] = [
                        'id' => $b->id,
                        'user_name' => $b->user->name ?? 'Guest',
                        'package_name' => $b->package->name ?? 'Package N/A',
                        'payment_status' => $b->payment_status ?? 'Pending',
                        'total_person' => $b->total_person,
                        'paid_amount' => $b->paid_amount ?? '0',
                        'created_at_human' => $b->created_at->setTimezone('Asia/Manila')->diffForHumans(),
                        'url' => route('admin_tour_booking', ['tour_id' => $b->tour_id, 'package_id' => $b->package_id]),
                    ];
                }
            }
        } catch (\Throwable $e) {}

        try {
            if (\Schema::hasColumn('messages', 'admin_viewed_at')) {
                $all_messages = Message::with(['comments', 'user'])->get();
                $filtered = $all_messages->filter(function ($m) {
                    if (is_null($m->admin_viewed_at)) return true;
                    return $m->comments->contains(fn($c) => $c->type == 'User' && $c->created_at > $m->admin_viewed_at);
                })->sortByDesc('created_at')->take(5)->values();
                $messages_count = Message::with(['comments'])->get()->filter(function ($m) {
                    if (is_null($m->admin_viewed_at)) return true;
                    return $m->comments->contains(fn($c) => $c->type == 'User' && $c->created_at > $m->admin_viewed_at);
                })->count();
                foreach ($filtered as $m) {
                    $messages[] = [
                        'id' => $m->id,
                        'user_name' => $m->user->name ?? 'Guest',
                        'is_new' => is_null($m->admin_viewed_at),
                        'created_at_human' => $m->created_at->setTimezone('Asia/Manila')->diffForHumans(),
                        'url' => route('admin_message_detail', $m->id),
                    ];
                }
            }
        } catch (\Throwable $e) {}

        try {
            if (\Schema::hasColumn('reviews', 'admin_viewed_at')) {
                $reviews_count = Review::where(function ($q) {
                    $q->where('status', 'Pending')->orWhereNull('admin_viewed_at');
                })->count();
            } else {
                $reviews_count = Review::where('status', 'Pending')->count();
            }
        } catch (\Throwable $e) {}

        return response()->json([
            'bookings' => $bookings,
            'bookings_count' => $bookings_count,
            'messages' => $messages,
            'messages_count' => $messages_count,
            'reviews_count' => $reviews_count,
        ]);
    }
}
