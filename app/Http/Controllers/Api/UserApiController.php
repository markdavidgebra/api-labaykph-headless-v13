<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Inquiry;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Wishlist;
use App\Support\MailContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user ? with_upload_urls($user, ['photo']) : null,
        ]);
    }

    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;

        $completedQuery = Booking::where('user_id', $userId)->where('payment_status', 'Completed');
        $pendingQuery = Booking::where('user_id', $userId)->where('payment_status', 'Pending');

        $payments = Booking::with(['package', 'tour'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Booking $booking) {
                return [
                    'id' => $booking->id,
                    'invoice_no' => $booking->invoice_no,
                    'package_name' => $booking->package?->name,
                    'package_slug' => $booking->package?->slug,
                    'total_person' => $booking->total_person,
                    'paid_amount' => $booking->paid_amount,
                    'payment_method' => $booking->payment_method,
                    'payment_status' => $booking->payment_status,
                    'payment_proof' => $booking->payment_proof,
                    'payment_proof_url' => $booking->payment_proof_url,
                    'created_at' => $booking->created_at,
                ];
            });

        return response()->json([
            'total_completed_orders' => (clone $completedQuery)->count(),
            'total_pending_orders' => (clone $pendingQuery)->count(),
            'total_completed_amount' => (float) (clone $completedQuery)->sum('paid_amount'),
            'total_pending_amount' => (float) (clone $pendingQuery)->sum('paid_amount'),
            'total_paid_amount' => (float) Booking::where('user_id', $userId)->sum('paid_amount'),
            'payments' => $payments,
        ]);
    }

    public function bookings(Request $request)
    {
        $bookings = Booking::with(['tour', 'package'])->where('user_id', $request->user()->id)->get();

        return response()->json(['bookings' => $bookings]);
    }

    public function invoice(Request $request, string $invoiceNo)
    {
        $booking = Booking::with(['tour', 'package'])->where('invoice_no', $invoiceNo)
            ->where('user_id', $request->user()->id)->firstOrFail();
        $admin = Admin::where('id', 1)->first();

        return response()->json(['booking' => $booking, 'admin' => $admin]);
    }

    public function reviews(Request $request)
    {
        $reviews = Review::with('package')->where('user_id', $request->user()->id)->get();

        return response()->json(['reviews' => $reviews]);
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'package_id' => 'required',
            'rating' => 'required',
            'comment' => 'required',
        ]);

        Review::create([
            'user_id' => $request->user()->id,
            'package_id' => $request->package_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'Pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Review submitted. It will be posted after admin approval.']);
    }

    public function wishlist(Request $request)
    {
        $items = Wishlist::with('package')->where('user_id', $request->user()->id)->get();

        return response()->json(['wishlist' => $items]);
    }

    public function addWishlist(Request $request, int $packageId)
    {
        $exists = Wishlist::where('user_id', $request->user()->id)->where('package_id', $packageId)->exists();
        if ($exists) {
            return response()->json(['message' => 'This item is already in your wishlist.'], 422);
        }

        Wishlist::create([
            'user_id' => $request->user()->id,
            'package_id' => $packageId,
        ]);

        return response()->json(['success' => true, 'message' => 'Item added to wishlist.']);
    }

    public function deleteWishlist(Request $request, int $id)
    {
        Wishlist::where('id', $id)->where('user_id', $request->user()->id)->delete();

        return response()->json(['success' => true, 'message' => 'Wishlist item deleted.']);
    }

    public function profile(Request $request)
    {
        return response()->json(['user' => with_upload_urls($request->user(), ['photo'])]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->fill($request->only(['name', 'email', 'phone', 'country', 'address', 'state', 'city', 'zip']));

        if ($request->hasFile('photo')) {
            $request->validate(['photo' => 'mimes:jpg,jpeg,png,gif|max:2024']);
            $finalName = 'user_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finalName);
            if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
                unlink(public_path('uploads/'.$user->photo));
            }
            $user->photo = $finalName;
        }

        if ($request->password) {
            $request->validate(['password' => 'required', 'retype_password' => 'same:password']);
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json(['success' => true, 'user' => with_upload_urls($user, ['photo'])]);
    }

    public function messages(Request $request)
    {
        $message = Message::where('user_id', $request->user()->id)->first();
        if ($message) {
            Message::where('id', $message->id)->update(['user_viewed_at' => now()]);
            $comments = MessageComment::where('message_id', $message->id)->orderBy('id', 'desc')->get();
        } else {
            $comments = [];
        }

        $admin = Admin::where('id', 1)->first();

        return response()->json([
            'has_conversation' => (bool) $message,
            'comments' => $comments,
            'admin' => $admin ? with_upload_urls($admin, ['photo']) : null,
            'user' => with_upload_urls($request->user(), ['photo']),
        ]);
    }

    public function startMessage(Request $request)
    {
        $exists = Message::where('user_id', $request->user()->id)->exists();
        if ($exists) {
            return response()->json(['message' => 'You have already started a conversation.'], 422);
        }

        Message::create(['user_id' => $request->user()->id]);

        return response()->json(['success' => true]);
    }

    public function submitMessage(Request $request)
    {
        $request->validate(['comment' => 'required']);

        $message = Message::where('user_id', $request->user()->id)->first();
        if (!$message) {
            return response()->json(['success' => false, 'message' => 'No conversation found.'], 422);
        }

        $comment = MessageComment::create([
            'message_id' => $message->id,
            'sender_id' => $request->user()->id,
            'type' => 'User',
            'comment' => $request->comment,
        ]);

        try {
            if (Schema::hasTable('typing_indicators')) {
                DB::table('typing_indicators')->where('message_id', $message->id)->where('typer', 'user')->delete();
            }
        } catch (\Throwable $e) {
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'type' => $comment->type,
                'created_at' => $comment->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                'sender_name' => $request->user()->name ?? 'User',
            ],
        ]);
    }

    public function messagePoll(Request $request)
    {
        $message = Message::where('user_id', $request->user()->id)->first();
        if (!$message) {
            return response()->json(['comments' => [], 'admin_typing' => false]);
        }

        $query = MessageComment::where('message_id', $message->id)->where('type', 'Admin');
        if ($message->user_viewed_at) {
            $query->where('created_at', '>', $message->user_viewed_at);
        }
        $comments = $query->orderBy('id', 'asc')->get()->map(fn ($c) => [
            'id' => $c->id,
            'comment' => $c->comment,
            'type' => $c->type,
            'created_at' => $c->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
            'sender_name' => 'Admin',
        ]);

        $adminTyping = false;
        try {
            if (Schema::hasTable('typing_indicators')) {
                $adminTyping = DB::table('typing_indicators')
                    ->where('message_id', $message->id)->where('typer', 'admin')->exists();
            }
        } catch (\Throwable $e) {
        }

        return response()->json(['comments' => $comments, 'admin_typing' => $adminTyping]);
    }

    public function messageNotificationCount(Request $request)
    {
        $message = Message::where('user_id', $request->user()->id)->first();
        $count = 0;
        if ($message) {
            $query = MessageComment::where('message_id', $message->id)->where('type', 'Admin');
            if ($message->user_viewed_at) {
                $query->where('created_at', '>', $message->user_viewed_at);
            }
            $count = $query->count();
        }

        return response()->json(['count' => $count]);
    }

    public function bookingPayment(Request $request)
    {
        $request->validate([
            'tour_id' => 'required',
            'package_id' => 'required',
            'total_person' => 'required|integer|min:1',
            'ticket_price' => 'required|numeric',
            'payment_method' => 'required|in:QR Code,Cash',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'payment_proof.required' => 'Please upload a screenshot of your payment confirmation.',
            'payment_proof.image' => 'Payment proof must be an image file.',
            'payment_proof.max' => 'Payment proof must be 5MB or smaller.',
        ]);

        $proofName = null;
        if ($request->hasFile('payment_proof')) {
            $proofName = 'payment_proof_'.time().'.'.$request->file('payment_proof')->extension();
            $request->file('payment_proof')->move(public_path('uploads'), $proofName);
        }

        $booking = Booking::create([
            'tour_id' => $request->tour_id,
            'package_id' => $request->package_id,
            'user_id' => $request->user()->id,
            'total_person' => $request->total_person,
            'paid_amount' => $request->ticket_price,
            'payment_method' => $request->payment_method,
            'payment_proof' => $proofName,
            'payment_status' => 'Pending',
            'invoice_no' => time(),
        ]);

        $amount = number_format((float) $booking->paid_amount, 0, '.', ',');

        return response()->json([
            'success' => true,
            'message' => "Thank you for booking. We received your ₱{$amount} payment screenshot. Our sales team will verify and contact you soon! For fast approval, contact this number: 0917-138-0150.",
            'booking' => $booking,
        ]);
    }

    public function vipRequest(Request $request)
    {
        $data = $request->validate([
            'airlines' => 'required|string|max:255',
            'flight_class' => ['required', Rule::in(['Economy', 'Business'])],
            'date_of_travel' => 'required|date',
            'date_of_return' => 'required|date|after_or_equal:date_of_travel',
            'hotel_madinah' => 'required|string|max:255',
            'hotel_makkah' => 'required|string|max:255',
            'land_arrangement' => 'required|string|max:500',
            'assistance_type' => ['required', Rule::in(['Shaikh assistance', 'Company assistance'])],
            'land_transportation' => 'required|string|max:255',
            'other_recommendation' => 'nullable|string|max:2000',
            'pax' => 'required|integer|min:1|max:100',
        ]);

        $user = $request->user();

        $lines = [
            'VIP Travel Request',
            'Airlines: '.$data['airlines'],
            'Flight class: '.$data['flight_class'],
            'Date of travel: '.$data['date_of_travel'],
            'Date of return: '.$data['date_of_return'],
            'Hotel in Madinah: '.$data['hotel_madinah'],
            'Hotel in Makkah: '.$data['hotel_makkah'],
            'Land Arrangement: '.$data['land_arrangement'],
            'Assistance: '.$data['assistance_type'],
            'Land transportation: '.$data['land_transportation'],
            'Other recommendation: '.($data['other_recommendation'] ?: '—'),
            'Number of pax: '.$data['pax'],
        ];
        $plainMessage = implode("\n", $lines);

        Inquiry::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?: null,
            'type' => 'vip',
            'message' => $plainMessage,
            'admin_viewed_at' => null,
        ]);

        $admin = Admin::where('id', 1)->first();
        $setting = Setting::where('id', 1)->first();
        $toEmail = $admin?->email
            ?: ($setting?->top_bar_email ?: config('mail.from.address'));

        if ($toEmail) {
            try {
                $subject = 'VIP Travel Request — '.config('app.name');
                $body = MailContent::vipRequest(
                    $user->name ?? '',
                    $user->email ?? '',
                    $user->phone ?? '',
                    $data
                );
                Mail::to($toEmail)->send(new Websitemail($subject, $body));
            } catch (\Throwable $e) {
                Log::warning('Failed to send VIP request email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Asalamu Alikom. Your VIP request was submitted. Our team will contact you soon.',
        ]);
    }
}
