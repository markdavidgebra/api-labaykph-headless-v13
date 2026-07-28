<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutItem;
use App\Models\Amenity;
use App\Models\BlogCategory;
use App\Models\Booking;
use App\Models\ContactItem;
use App\Models\ContactOffice;
use App\Models\CounterItem;
use App\Models\Destination;
use App\Models\DestinationPhoto;
use App\Models\DestinationVideo;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\HomeItem;
use App\Models\Inquiry;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Package;
use App\Models\PackageAmenity;
use App\Models\PackageFaq;
use App\Models\PackageItinerary;
use App\Models\PackagePhoto;
use App\Models\PackageVideo;
use App\Models\Post;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Subscriber;
use App\Models\TeamMember;
use App\Models\TermPrivacyItem;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\User;
use App\Models\WelcomeItem;
use App\Models\SuperAdmin;
use App\Models\Wishlist;
use App\Support\AdminAccess;
use App\Mail\Websitemail;
use App\Support\MailContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    private array $resources = [
        'sliders' => Slider::class,
        'features' => Feature::class,
        'testimonials' => Testimonial::class,
        'team-members' => TeamMember::class,
        'faqs' => Faq::class,
        'blog-categories' => BlogCategory::class,
        'posts' => Post::class,
        'destinations' => Destination::class,
        'packages' => Package::class,
        'amenities' => Amenity::class,
        'tours' => Tour::class,
        'reviews' => Review::class,
        'users' => User::class,
        'subscribers' => Subscriber::class,
        'inquiries' => Inquiry::class,
    ];

    public function index(Request $request, string $resource)
    {
        AdminAccess::denyUnless(AdminAccess::canResource($request->user(), $resource, 'index'));

        $model = $this->resolveModel($resource);
        $query = $model::query();

        if ($resource === 'posts') {
            $query->with('blog_category');
        }
        if ($resource === 'packages') {
            $query->with('destination')
                ->withCount(['tours', 'package_photos', 'package_videos', 'package_amenities', 'package_itineraries', 'package_faqs']);
        }
        if ($resource === 'destinations') {
            $query->withCount(['packages', 'photos', 'videos']);
        }
        if ($resource === 'tours') {
            $query->with(['package', 'bookings']);
        }
        if ($resource === 'reviews') {
            $query->with(['user', 'package']);
        }
        if ($resource === 'users') {
            User::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);
        }
        if ($resource === 'subscribers') {
            Subscriber::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);

            if ($search = trim((string) $request->query('search', ''))) {
                $query->where('email', 'like', '%'.$search.'%');
            }
            if ($status = $request->query('status')) {
                $query->where('status', $status);
            }
        }
        if ($resource === 'reviews') {
            Review::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);
        }
        if ($resource === 'inquiries') {
            $type = $request->query('type', 'general');
            $query->where('type', $type === 'vip' ? 'vip' : 'general');
        }

        if ($resource === 'packages' && $request->query('sort') === 'name') {
            $query->orderBy('name');
        } else {
            $query->orderBy('id', 'desc');
        }

        $perPage = min(max((int) $request->query('per_page', 20), 1), 500);
        $items = $query->paginate($perPage);

        if ($resource === 'tours') {
            $items->getCollection()->transform(function ($tour) {
                $tour->unviewed_bookings_count = $tour->package_id
                    ? Booking::where('tour_id', $tour->id)
                        ->where('package_id', $tour->package_id)
                        ->whereNull('admin_viewed_at')
                        ->count()
                    : 0;

                return $tour;
            });
        }

        if ($resource === 'inquiries' && $request->query('type') === 'vip') {
            $items->getCollection()->transform(function ($inquiry) {
                $meta = $this->messageUnreadMetaForEmail($inquiry->email);
                $inquiry->message_id = $meta['message_id'];
                $inquiry->unread_count = $meta['unread_count'];
                $inquiry->has_unread = $meta['has_unread'];

                return $inquiry;
            });
        }

        return response()->json($items);
    }

    public function show(string $resource, int $id)
    {
        AdminAccess::denyUnless(AdminAccess::canResource(request()->user(), $resource, 'show'));

        $model = $this->resolveModel($resource);
        $item = $model::findOrFail($id);

        if ($resource === 'destinations') {
            return response()->json([
                'item' => $item,
                'photos' => DestinationPhoto::where('destination_id', $id)->get(),
                'videos' => DestinationVideo::where('destination_id', $id)->get(),
            ]);
        }

        if ($resource === 'packages') {
            return response()->json([
                'item' => $item,
                'amenities' => PackageAmenity::with('amenity')->where('package_id', $id)->get(),
                'itineraries' => PackageItinerary::where('package_id', $id)->get(),
                'photos' => PackagePhoto::where('package_id', $id)->get(),
                'videos' => PackageVideo::where('package_id', $id)->get(),
                'faqs' => PackageFaq::where('package_id', $id)->get(),
            ]);
        }

        if ($resource === 'messages') {
            $comments = MessageComment::where('message_id', $id)->orderBy('id', 'desc')->get();
            Message::where('id', $id)->update(['admin_viewed_at' => now()]);

            return response()->json(['item' => Message::with('user')->findOrFail($id), 'comments' => $comments]);
        }

        if ($resource === 'tours') {
            return response()->json(['item' => Tour::with('package')->findOrFail($id)]);
        }

        if ($resource === 'users') {
            $item->admin_viewed_at = now();
            $item->save();
        }

        return response()->json(['item' => $item]);
    }

    public function store(Request $request, string $resource)
    {
        AdminAccess::denyUnless(AdminAccess::canResource($request->user(), $resource, 'store'));

        $model = $this->resolveModel($resource);
        $data = $this->prepareData($request, $resource);
        $item = $model::create($data);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function update(Request $request, string $resource, int $id)
    {
        AdminAccess::denyUnless(AdminAccess::canResource($request->user(), $resource, 'update'));

        $model = $this->resolveModel($resource);
        $item = $model::findOrFail($id);
        $data = $this->prepareData($request, $resource, $item);
        $item->update($data);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function destroy(string $resource, int $id)
    {
        AdminAccess::denyUnless(AdminAccess::canResource(request()->user(), $resource, 'destroy'));

        if ($resource === 'tours') {
            $bookingCount = Booking::where('tour_id', $id)->count();
            if ($bookingCount > 0) {
                return response()->json([
                    'message' => 'This tour has bookings. So, it can not be deleted.',
                ], 422);
            }
        }

        if ($resource === 'blog-categories') {
            $postCount = Post::where('blog_category_id', $id)->count();
            if ($postCount > 0) {
                return response()->json([
                    'message' => 'This Blog Category is in use. So you can not delete it.',
                ], 422);
            }
        }

        if ($resource === 'users') {
            if (Review::where('user_id', $id)->exists()) {
                return response()->json(['message' => 'User can not be deleted because it has some reviews'], 422);
            }
            if (Message::where('user_id', $id)->exists()) {
                return response()->json(['message' => 'User can not be deleted because it has some messages'], 422);
            }
            if (Wishlist::where('user_id', $id)->exists()) {
                return response()->json(['message' => 'User can not be deleted because it has some wishlist'], 422);
            }
            if (Booking::where('user_id', $id)->exists()) {
                return response()->json(['message' => 'User can not be deleted because it has some bookings'], 422);
            }

            $user = User::findOrFail($id);
            $this->unlinkUpload($user->photo);
            $user->delete();

            return response()->json(['success' => true]);
        }

        $model = $this->resolveModel($resource);
        $model::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function messages()
    {
        $messages = Message::with('user')->orderBy('updated_at', 'desc')->paginate(20);

        $messages->getCollection()->transform(function ($message) {
            $meta = $this->messageUnreadMeta($message);
            $message->unread_count = $meta['unread_count'];
            $message->has_unread = $meta['has_unread'];

            return $message;
        });

        return response()->json($messages);
    }

    public function approveReview(int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));
        $review = Review::findOrFail($id);
        $review->status = 'Approved';
        $review->save();

        $package = Package::find($review->package_id);
        if ($package) {
            $approved = Review::where('package_id', $package->id)->where('status', 'Approved')->get();
            $package->total_rating = $approved->count();
            $package->total_score = $approved->sum('rating');
            $package->save();
        }

        return response()->json(['success' => true]);
    }

    public function rejectReview(int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));
        Review::findOrFail($id)->update(['status' => 'Rejected']);

        return response()->json(['success' => true]);
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'package', 'tour'])->orderBy('id', 'desc');

        if ($status = trim((string) $request->query('status', ''))) {
            $query->where('payment_status', $status);
        }

        if ($search = trim((string) $request->query('search', ''))) {
            $like = '%'.$search.'%';
            $looksLikeDate = (bool) preg_match(
                '/\d{4}-\d{1,2}-\d{1,2}|\d{1,2}\/\d{1,2}\/\d{2,4}|[A-Za-z]{3,9}\s+\d{1,2}/',
                $search
            );

            $query->where(function ($q) use ($search, $like, $looksLikeDate) {
                $q->where('invoice_no', 'like', $like)
                    ->orWhereHas('user', function ($userQuery) use ($like) {
                        $userQuery->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like);
                    })
                    ->orWhereRaw("DATE_FORMAT(created_at, '%b %e, %Y') LIKE ?", [$like])
                    ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') LIKE ?", [$like])
                    ->orWhereRaw("DATE_FORMAT(created_at, '%M %e, %Y') LIKE ?", [$like])
                    ->orWhereRaw("DATE_FORMAT(created_at, '%m/%d/%Y') LIKE ?", [$like]);

                // Avoid treating numeric reference nos (unix timestamps) as dates.
                if ($looksLikeDate) {
                    try {
                        $parsed = \Carbon\Carbon::parse($search);
                        $q->orWhereDate('created_at', $parsed->toDateString());
                    } catch (\Throwable) {
                        // Not a parseable date — reference/name search still applies.
                    }
                }
            });
        }

        return response()->json([
            'bookings' => $query->paginate(20),
            'summary' => [
                'total_amount' => (float) Booking::sum('paid_amount'),
                'completed_amount' => (float) Booking::where('payment_status', 'Completed')->sum('paid_amount'),
                'pending_amount' => (float) Booking::where('payment_status', 'Pending')->sum('paid_amount'),
                'completed_count' => Booking::where('payment_status', 'Completed')->count(),
                'pending_count' => Booking::where('payment_status', 'Pending')->count(),
                'total_count' => Booking::count(),
            ],
        ]);
    }

    public function storeDestinationPhoto(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff($request->user()));
        Destination::findOrFail($id);
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $finalName = 'destination_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finalName);

        $photo = DestinationPhoto::create([
            'destination_id' => $id,
            'photo' => $finalName,
        ]);

        return response()->json(['success' => true, 'item' => $photo], 201);
    }

    public function destroyDestinationPhoto(int $id, int $photoId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        $photo = DestinationPhoto::where('destination_id', $id)->where('id', $photoId)->firstOrFail();
        $photoPath = public_path('uploads/'.$photo->photo);
        if ($photo->photo && file_exists($photoPath)) {
            unlink($photoPath);
        }
        $photo->delete();

        return response()->json(['success' => true]);
    }

    public function storeDestinationVideo(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Destination::findOrFail($id);
        $request->validate(['video' => 'required|string']);

        $videoId = extract_youtube_video_id($request->video);
        if (!$videoId) {
            return response()->json(['message' => 'Invalid YouTube URL. Please enter a valid YouTube link or video ID.'], 422);
        }

        $video = DestinationVideo::create([
            'destination_id' => $id,
            'video' => $videoId,
        ]);

        return response()->json(['success' => true, 'item' => $video], 201);
    }

    public function destroyDestinationVideo(int $id, int $videoId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        DestinationVideo::where('destination_id', $id)->where('id', $videoId)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    public function storePackagePhoto(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Package::findOrFail($id);
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $finalName = 'package_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $finalName);

        $photo = PackagePhoto::create([
            'package_id' => $id,
            'photo' => $finalName,
        ]);

        return response()->json(['success' => true, 'item' => $photo], 201);
    }

    public function destroyPackagePhoto(int $id, int $photoId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        $photo = PackagePhoto::where('package_id', $id)->where('id', $photoId)->firstOrFail();
        $photoPath = public_path('uploads/'.$photo->photo);
        if ($photo->photo && file_exists($photoPath)) {
            unlink($photoPath);
        }
        $photo->delete();

        return response()->json(['success' => true]);
    }

    public function storePackageVideo(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Package::findOrFail($id);
        $request->validate(['video' => 'required|string']);

        $videoId = extract_youtube_video_id($request->video);
        if (!$videoId) {
            return response()->json(['message' => 'Invalid YouTube URL. Please enter a valid YouTube link or video ID.'], 422);
        }

        $video = PackageVideo::create([
            'package_id' => $id,
            'video' => $videoId,
        ]);

        return response()->json(['success' => true, 'item' => $video], 201);
    }

    public function destroyPackageVideo(int $id, int $videoId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        PackageVideo::where('package_id', $id)->where('id', $videoId)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    public function storePackageAmenity(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Package::findOrFail($id);
        $request->validate([
            'amenity_id' => 'required|integer|exists:amenities,id',
            'type' => 'required|in:Include,Exclude',
        ]);

        $exists = PackageAmenity::where('package_id', $id)
            ->where('amenity_id', $request->amenity_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'This item is already inserted.'], 422);
        }

        $item = PackageAmenity::create([
            'package_id' => $id,
            'amenity_id' => $request->amenity_id,
            'type' => $request->type,
        ]);

        return response()->json(['success' => true, 'item' => $item->load('amenity')], 201);
    }

    public function destroyPackageAmenity(int $id, int $amenityId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        PackageAmenity::where('package_id', $id)->where('id', $amenityId)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    public function storePackageItinerary(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Package::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $item = PackageItinerary::create([
            'package_id' => $id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function destroyPackageItinerary(int $id, int $itineraryId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        PackageItinerary::where('package_id', $id)->where('id', $itineraryId)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    public function storePackageFaq(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Package::findOrFail($id);
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $item = PackageFaq::create([
            'package_id' => $id,
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        return response()->json(['success' => true, 'item' => $item], 201);
    }

    public function destroyPackageFaq(int $id, int $faqId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        PackageFaq::where('package_id', $id)->where('id', $faqId)->firstOrFail()->delete();

        return response()->json(['success' => true]);
    }

    public function tourBookings(int $tourId, int $packageId)
    {
        Tour::where('id', $tourId)->where('package_id', $packageId)->firstOrFail();

        Booking::where('tour_id', $tourId)
            ->where('package_id', $packageId)
            ->whereNull('admin_viewed_at')
            ->update(['admin_viewed_at' => now()]);

        $bookings = Booking::with('user')
            ->where('tour_id', $tourId)
            ->where('package_id', $packageId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['bookings' => $bookings]);
    }

    public function destroyTourBooking(int $bookingId)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        Booking::findOrFail($bookingId)->delete();

        return response()->json(['success' => true]);
    }

    public function approveTourBooking(int $bookingId)
    {
        AdminAccess::denyUnless(AdminAccess::canApprovePayments(request()->user()));

        $booking = Booking::with(['user', 'package', 'tour'])->findOrFail($bookingId);
        $booking->update([
            'payment_status' => 'Completed',
            'payment_disapprove_reason' => null,
        ]);

        $user = $booking->user;
        if ($user?->email) {
            try {
                $bookingUrl = frontend_url('user/booking/'.$booking->invoice_no);
                $subject = 'Payment approved — '.config('app.name');
                $message = MailContent::paymentApproved(
                    $user->name ?? '',
                    $booking->package?->name ?? 'your package',
                    (string) $booking->invoice_no,
                    (float) $booking->paid_amount,
                    $bookingUrl
                );
                Mail::to($user->email)->send(new Websitemail($subject, $message));
            } catch (\Throwable $e) {
                Log::warning('Failed to send payment approval email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment approved. Client has been notified by email.',
        ]);
    }

    public function disapproveTourBooking(Request $request, int $bookingId)
    {
        AdminAccess::denyUnless(AdminAccess::canApprovePayments($request->user()));

        $data = $request->validate([
            'reason' => 'required|string|min:5|max:2000',
        ]);

        $booking = Booking::with(['user', 'package', 'tour'])->findOrFail($bookingId);
        $reason = trim($data['reason']);

        $booking->update([
            'payment_status' => 'Disapproved',
            'payment_disapprove_reason' => $reason,
        ]);

        $user = $booking->user;
        if ($user?->email) {
            try {
                $bookingUrl = frontend_url('user/booking/'.$booking->invoice_no);
                $subject = 'Payment not approved — '.config('app.name');
                $message = MailContent::paymentDisapproved(
                    $user->name ?? '',
                    $booking->package?->name ?? 'your package',
                    (string) $booking->invoice_no,
                    (float) $booking->paid_amount,
                    $reason,
                    $bookingUrl
                );
                Mail::to($user->email)->send(new Websitemail($subject, $message));
            } catch (\Throwable $e) {
                Log::warning('Failed to send payment disapproval email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment disapproved. Client has been notified by email.',
        ]);
    }

    public function tourInvoice(string $invoiceNo)
    {
        $booking = Booking::with(['user', 'tour', 'package'])
            ->where('invoice_no', $invoiceNo)
            ->firstOrFail();
        $setting = Setting::where('id', 1)->first();

        return response()->json([
            'booking' => $booking,
            'setting' => $setting,
        ]);
    }

    public function forceDestroyTour(Request $request, int $id)
    {
        $this->ensureSuperAdmin($request);

        $tour = Tour::findOrFail($id);
        Booking::where('tour_id', $id)->delete();
        $tour->delete();

        return response()->json(['success' => true]);
    }

    public function forceDestroyDestination(Request $request, int $id)
    {
        $this->ensureSuperAdmin($request);

        $destination = Destination::findOrFail($id);
        $packageIds = Package::where('destination_id', $id)->pluck('id');

        foreach ($packageIds as $packageId) {
            $this->purgePackage($packageId);
        }

        foreach (DestinationPhoto::where('destination_id', $id)->get() as $photo) {
            $this->unlinkUpload($photo->photo);
            $photo->delete();
        }
        DestinationVideo::where('destination_id', $id)->delete();

        $this->unlinkUpload($destination->featured_photo);
        $destination->delete();

        return response()->json(['success' => true]);
    }

    public function forceDestroyPackage(Request $request, int $id)
    {
        $this->ensureSuperAdmin($request);

        $package = Package::findOrFail($id);
        $this->purgePackage($id);
        $this->unlinkUpload($package->featured_photo);
        $this->unlinkUpload($package->banner);
        $package->delete();

        return response()->json(['success' => true]);
    }

    public function forceDestroyUser(Request $request, int $id)
    {
        $this->ensureSuperAdmin($request);

        $user = User::findOrFail($id);
        $messageIds = Message::where('user_id', $id)->pluck('id');

        if (\Schema::hasTable('typing_indicators') && $messageIds->isNotEmpty()) {
            \DB::table('typing_indicators')->whereIn('message_id', $messageIds)->delete();
        }

        MessageComment::whereIn('message_id', $messageIds)->delete();
        Message::where('user_id', $id)->delete();
        Booking::where('user_id', $id)->delete();
        Review::where('user_id', $id)->delete();
        Wishlist::where('user_id', $id)->delete();
        $this->unlinkUpload($user->photo);
        $user->delete();

        return response()->json(['success' => true]);
    }

    public function messageThreadForUser(int $userId)
    {
        AdminAccess::denyUnless(AdminAccess::canResource(request()->user(), 'messages', 'show'));

        $message = Message::where('user_id', $userId)->first();
        if (!$message) {
            $message = Message::create(['user_id' => $userId]);
        }

        return response()->json(['message_id' => $message->id]);
    }

    public function vipThread(int $id)
    {
        AdminAccess::denyUnless(AdminAccess::canReplyMessages(request()->user()));

        $inquiry = Inquiry::where('id', $id)->where('type', 'vip')->firstOrFail();
        $user = User::where('email', $inquiry->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'No registered user found for this VIP request email.',
                'message_id' => null,
                'comments' => [],
                'user' => null,
            ], 422);
        }

        $message = Message::firstOrCreate(['user_id' => $user->id]);
        Message::where('id', $message->id)->update(['admin_viewed_at' => now()]);

        $comments = MessageComment::where('message_id', $message->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'message_id' => $message->id,
            'user' => $user,
            'comments' => $comments,
            'inquiry_id' => $inquiry->id,
        ]);
    }

    public function replyToMessage(Request $request, int $id)
    {
        AdminAccess::denyUnless(AdminAccess::canReplyMessages($request->user()));

        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $message = Message::with('user')->findOrFail($id);
        $adminUser = $request->user();

        $comment = MessageComment::create([
            'message_id' => $message->id,
            'sender_id' => (int) ($adminUser->id ?? 1),
            'type' => 'Admin',
            'comment' => $request->comment,
        ]);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('typing_indicators')) {
                \Illuminate\Support\Facades\DB::table('typing_indicators')
                    ->where('message_id', $message->id)
                    ->where('typer', 'admin')
                    ->delete();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        Message::where('id', $message->id)->update([
            'admin_viewed_at' => now(),
            'updated_at' => now(),
        ]);

        $userEmail = $message->user?->email;
        if ($userEmail) {
            try {
                $link = frontend_url('user/messages');
                $subject = 'New message from '.config('app.name');
                $body = MailContent::greeting($message->user->name ?? '')
                    .'<br><br>You have a new message from our team.'
                    .MailContent::button($link, 'Open Messages')
                    .MailContent::fallbackLink($link);
                Mail::to($userEmail)->send(new Websitemail($subject, $body));
            } catch (\Throwable $e) {
                Log::warning('Failed to send admin reply email', [
                    'message_id' => $message->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'type' => $comment->type,
                'created_at' => $comment->created_at,
                'sender_name' => $adminUser->name ?? 'Admin',
            ],
        ]);
    }

    public function showContactOffice(int $id)
    {
        return response()->json(['office' => ContactOffice::findOrFail($id)]);
    }

    public function storeContactOffice(Request $request)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff($request->user()));
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $office = ContactOffice::create([
            'name' => $request->name,
            'address' => $request->address,
            'landline' => $request->landline,
            'globe' => $request->globe,
            'smart' => $request->smart,
            'sort_order' => (ContactOffice::max('sort_order') ?? 0) + 1,
        ]);

        return response()->json(['success' => true, 'office' => $office], 201);
    }

    public function updateContactOffice(Request $request, int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff($request->user()));
        $request->validate([
            'name' => ['required', 'max:255'],
        ]);

        $office = ContactOffice::findOrFail($id);
        $office->update([
            'name' => $request->name,
            'address' => $request->address,
            'landline' => $request->landline,
            'globe' => $request->globe,
            'smart' => $request->smart,
        ]);

        return response()->json(['success' => true, 'office' => $office]);
    }

    public function destroyContactOffice(int $id)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));
        ContactOffice::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }

    public function singleton(string $key)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff(request()->user()));

        $map = [
            'welcome' => WelcomeItem::class,
            'counter' => CounterItem::class,
            'home' => HomeItem::class,
            'about' => AboutItem::class,
            'contact' => ContactItem::class,
            'terms' => TermPrivacyItem::class,
            'settings' => Setting::class,
        ];

        if (!isset($map[$key])) {
            abort(404);
        }

        $model = $map[$key];
        $item = $model::where('id', 1)->first();

        if ($key === 'contact') {
            return response()->json([
                'item' => $item,
                'offices' => ContactOffice::orderBy('sort_order')->orderBy('id')->get(),
            ]);
        }

        return response()->json(['item' => $item]);
    }

    public function updateSingleton(Request $request, string $key)
    {
        AdminAccess::denyUnless(! AdminAccess::isStaff($request->user()));
        $map = [
            'welcome' => WelcomeItem::class,
            'counter' => CounterItem::class,
            'home' => HomeItem::class,
            'about' => AboutItem::class,
            'contact' => ContactItem::class,
            'terms' => TermPrivacyItem::class,
            'settings' => Setting::class,
        ];

        if (!isset($map[$key])) {
            abort(404);
        }

        $model = $map[$key];
        $item = $model::where('id', 1)->firstOrFail();
        $item->fill($request->except([
            '_token',
            'id',
            'created_at',
            'updated_at',
            'photo',
            'logo',
            'favicon',
            'banner',
            'payment_qr',
            'testimonial_background',
            'cta_background',
        ]));
        $this->handleUpload($request, $item, [
            'photo',
            'logo',
            'favicon',
            'banner',
            'payment_qr',
            'testimonial_background',
            'cta_background',
        ]);
        $item->save();

        return response()->json(['success' => true, 'item' => $item]);
    }

    private function resolveModel(string $resource): string
    {
        if ($resource === 'messages') {
            return Message::class;
        }

        if (!isset($this->resources[$resource])) {
            abort(404, 'Resource not found');
        }

        return $this->resources[$resource];
    }

    private function prepareData(Request $request, string $resource, $existing = null): array
    {
        if ($resource === 'subscribers') {
            $email = strtolower(trim((string) $request->input('email', '')));
            $request->merge(['email' => $email]);
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('subscribers', 'email')->ignore($existing?->id),
                ],
                'status' => 'nullable|string|in:Active,Pending,Inactive',
            ], [
                'email.unique' => 'This email is already subscribed.',
            ]);
        }

        $uploadFields = ['photo', 'logo', 'favicon', 'featured_photo', 'banner'];
        $data = $request->except(array_merge(['_token', 'confirm_password'], $uploadFields));

        if (in_array($resource, ['destinations', 'packages', 'team-members', 'blog-categories']) && $request->name && !$request->slug) {
            $data['slug'] = Str::slug($request->name);
        }

        if ($resource === 'posts' && $request->title && !$request->slug) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($resource === 'users' && $request->password) {
            $data['password'] = bcrypt($request->password);
        }

        if ($resource === 'destinations' && !$existing) {
            $data['view_count'] = $data['view_count'] ?? 1;
        }

        if ($resource === 'packages' && !$existing) {
            $data['total_rating'] = $data['total_rating'] ?? 0;
            $data['total_score'] = $data['total_score'] ?? 0;
        }

        if ($resource === 'packages' && array_key_exists('sold_out', $data)) {
            $data['sold_out'] = filter_var($data['sold_out'], FILTER_VALIDATE_BOOLEAN);
        }

        if ($resource === 'packages') {
            foreach (['price', 'old_price'] as $priceField) {
                if (array_key_exists($priceField, $data) && ($data[$priceField] === '' || $data[$priceField] === null)) {
                    $data[$priceField] = null;
                }
            }
        }

        $model = $existing ?? new ($this->resolveModel($resource));
        $this->handleUpload($request, $model, $uploadFields);

        foreach ($uploadFields as $field) {
            if (!empty($model->$field)) {
                $data[$field] = $model->$field;
            }
        }

        return $data;
    }

    private function handleUpload(Request $request, $model, array $fields): void
    {
        foreach ($fields as $field) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $prefix = match (true) {
                $field === 'featured_photo' && $model instanceof Destination => 'destination_featured_',
                $field === 'featured_photo' && $model instanceof Package => 'package_featured_',
                $field === 'banner' => 'package_banner_',
                $field === 'photo' && $model instanceof Slider => 'slider_',
                $field === 'photo' && $model instanceof WelcomeItem => 'welcome_item_',
                $field === 'photo' && $model instanceof Testimonial => 'testimonial_',
                $field === 'photo' && $model instanceof TeamMember => 'team_member_',
                $field === 'photo' && $model instanceof Post => 'post_',
                $field === 'photo' && $model instanceof User => 'user_',
                default => $field.'_',
            };

            $finalName = $prefix.time().'.'.$request->file($field)->extension();
            $request->file($field)->move(public_path('uploads'), $finalName);

            if (!empty($model->$field) && file_exists(public_path('uploads/'.$model->$field))) {
                unlink(public_path('uploads/'.$model->$field));
            }

            $model->$field = $finalName;
        }
    }

    private function messageUnreadMeta(Message $message): array
    {
        if (is_null($message->admin_viewed_at)) {
            $unread = MessageComment::where('message_id', $message->id)
                ->where('type', 'User')
                ->count();
            if ($unread < 1) {
                $unread = 1;
            }
        } else {
            $unread = MessageComment::where('message_id', $message->id)
                ->where('type', 'User')
                ->where('created_at', '>', $message->admin_viewed_at)
                ->count();
        }

        return [
            'message_id' => $message->id,
            'unread_count' => $unread,
            'has_unread' => $unread > 0,
        ];
    }

    private function messageUnreadMetaForEmail(?string $email): array
    {
        if (!$email) {
            return ['message_id' => null, 'unread_count' => 0, 'has_unread' => false];
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return ['message_id' => null, 'unread_count' => 0, 'has_unread' => false];
        }

        $message = Message::where('user_id', $user->id)->first();
        if (!$message) {
            return ['message_id' => null, 'unread_count' => 0, 'has_unread' => false];
        }

        return $this->messageUnreadMeta($message);
    }

    private function ensureSuperAdmin(Request $request): void
    {
        if (!$request->user() instanceof SuperAdmin) {
            abort(403, 'Only super admin can perform this action.');
        }
    }

    private function unlinkUpload(?string $filename): void
    {
        if (!$filename) {
            return;
        }
        $path = public_path('uploads/'.$filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function purgePackage(int $packageId): void
    {
        Booking::where('package_id', $packageId)->delete();
        Review::where('package_id', $packageId)->delete();
        Wishlist::where('package_id', $packageId)->delete();
        Tour::where('package_id', $packageId)->delete();

        foreach (PackagePhoto::where('package_id', $packageId)->get() as $photo) {
            $this->unlinkUpload($photo->photo);
            $photo->delete();
        }

        PackageVideo::where('package_id', $packageId)->delete();
        PackageAmenity::where('package_id', $packageId)->delete();
        PackageItinerary::where('package_id', $packageId)->delete();
        PackageFaq::where('package_id', $packageId)->delete();

        $package = Package::find($packageId);
        if ($package) {
            $this->unlinkUpload($package->featured_photo);
            $this->unlinkUpload($package->banner);
            $package->delete();
        }
    }
}
