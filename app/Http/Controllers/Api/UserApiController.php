<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        return response()->json([
            'total_completed_orders' => Booking::where('user_id', $userId)->where('payment_status', 'Completed')->count(),
            'total_pending_orders' => Booking::where('user_id', $userId)->where('payment_status', 'Pending')->count(),
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
            'payment_method' => 'required|in:Cash',
        ]);

        $booking = Booking::create([
            'tour_id' => $request->tour_id,
            'package_id' => $request->package_id,
            'user_id' => $request->user()->id,
            'total_person' => $request->total_person,
            'paid_amount' => $request->ticket_price,
            'payment_method' => 'Cash',
            'payment_status' => 'Pending',
            'invoice_no' => time(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for booking. Sales person will contact you soon!',
            'booking' => $booking,
        ]);
    }
}
