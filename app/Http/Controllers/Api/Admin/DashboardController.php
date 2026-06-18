<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Package;
use App\Models\Post;
use App\Models\Review;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $thisMonthBookings = Booking::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)->count();
        $lastMonthBookings = Booking::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)->count();

        return response()->json([
            'totals' => [
                'sliders' => Slider::count(),
                'testimonials' => Testimonial::count(),
                'team_members' => TeamMember::count(),
                'posts' => Post::count(),
                'destinations' => Destination::count(),
                'packages' => Package::count(),
                'users' => User::where('status', 1)->count(),
                'subscribers' => Subscriber::where('status', 'Active')->count(),
                'tours' => Tour::count(),
            ],
            'analytics' => [
                'recent_posts' => Post::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
                'recent_users' => User::where('status', 1)->where('created_at', '>=', Carbon::now()->subDays(30))->count(),
                'recent_subscribers' => Subscriber::where('status', 'Active')->where('created_at', '>=', Carbon::now()->subDays(30))->count(),
                'recent_packages' => Package::where('created_at', '>=', Carbon::now()->subDays(30))->count(),
                'bookings_growth' => $lastMonthBookings > 0
                    ? round((($thisMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1) : 0,
            ],
            'recent_bookings' => Booking::with(['user', 'package', 'tour'])->orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_users' => User::where('status', 1)->orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_messages' => Message::with('user')->orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_reviews' => Review::with(['user', 'package'])->orderBy('created_at', 'desc')->limit(5)->get(),
            'notifications' => $this->notificationCounts(),
        ]);
    }

    public function notificationsPoll()
    {
        return response()->json($this->notificationCounts());
    }

    public function markBookingViewed()
    {
        Booking::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markUserViewed()
    {
        User::where('status', 1)->whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markReviewViewed()
    {
        Review::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);

        return response()->json(['success' => true]);
    }

    private function notificationCounts(): array
    {
        $unviewedMessages = 0;
        $allMessages = Message::with(['comments' => fn ($q) => $q->orderBy('created_at', 'desc')])->get();
        foreach ($allMessages as $msg) {
            if (is_null($msg->admin_viewed_at)) {
                $unviewedMessages++;
            } else {
                $newUserComments = MessageComment::where('message_id', $msg->id)
                    ->where('type', 'User')
                    ->where('created_at', '>', $msg->admin_viewed_at)->count();
                if ($newUserComments > 0) {
                    $unviewedMessages++;
                }
            }
        }

        return [
            'unviewed_bookings' => Booking::whereNull('admin_viewed_at')->count(),
            'unviewed_users' => User::where('status', 1)->whereNull('admin_viewed_at')->count(),
            'unviewed_reviews' => Review::where(function ($query) {
                $query->where('status', 'Pending')->orWhereNull('admin_viewed_at');
            })->count(),
            'unviewed_messages' => $unviewedMessages,
            'unviewed_subscribers' => Subscriber::whereNull('admin_viewed_at')->count(),
        ];
    }
}
