<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Message;
use App\Models\MessageComment;
use App\Mail\Websitemail;

class UserController extends Controller
{
    public function dashboard()
    {
        $total_completed_orders = Booking::where('user_id',Auth::guard('web')->user()->id)->where('payment_status','Completed')->count();
        $total_pending_orders = Booking::where('user_id',Auth::guard('web')->user()->id)->where('payment_status','Pending')->count();
        return view('user.dashboard', compact('total_completed_orders', 'total_pending_orders'));
    }

    public function booking()
    {
        $all_data = Booking::with(['tour','package'])->where('user_id',Auth::guard('web')->user()->id)->get();
        return view('user.booking', compact('all_data'));
    }

    public function invoice($invoice_no)
    {
        $admin_data = Admin::where('id',1)->first();
        $booking = Booking::with(['tour','package'])->where('invoice_no',$invoice_no)->first();
        return view('user.invoice', compact('invoice_no', 'booking', 'admin_data'));
    }

    public function review()
    {
        $reviews = Review::with('package')->where('user_id',Auth::guard('web')->user()->id)->get();
        //dd($reviews);
        return view('user.review', compact('reviews'));
    }

    public function wishlist()
    {
        $wishlist = Wishlist::with('package')->where('user_id',Auth::guard('web')->user()->id)->get();
        return view('user.wishlist', compact('wishlist'));
    }

    public function wishlist_delete($id)
    {
        $obj = Wishlist::where('id',$id)->first();
        $obj->delete();
        return redirect()->back()->with('success', 'Wishlist item is deleted successfully!');
    }

    public function message()
    {
        $message_check = Message::where('user_id',Auth::guard('web')->user()->id)->count();
        $message = Message::where('user_id',Auth::guard('web')->user()->id)->first();
        
        // Mark message as viewed by user when they visit the page
        if($message) {
            Message::where('id', $message->id)->update(['user_viewed_at' => now()]);
            $message_comments = MessageComment::where('message_id',$message->id)->orderBy('id','desc')->get();
        } else {
            $message_comments = [];
        }
        

        return view('user.message', compact('message_check', 'message_comments'));
    }

    public function message_start()
    {
        $message_check = Message::where('user_id',Auth::guard('web')->user()->id)->count();
        if($message_check > 0) {
            return redirect()->back()->with('error', 'You have already started a conversation!');
        }

        $obj = new Message;
        $obj->user_id = Auth::guard('web')->user()->id;
        $obj->save();
        
        return redirect()->back();
    }

    public function message_submit(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();

        if (!$message) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No conversation found. Please start a new conversation first.']);
            }
            return redirect()->back()->with('error', 'No conversation found. Please start a new conversation first.');
        }

        $obj = new MessageComment;
        $obj->message_id = $message->id;
        $obj->sender_id = Auth::guard('web')->user()->id;
        $obj->type = 'User';
        $obj->comment = $request->comment;
        $obj->save();

        try {
            if (\Schema::hasTable('typing_indicators')) {
                \DB::table('typing_indicators')->where('message_id', $message->id)->where('typer', 'user')->delete();
            }
        } catch (\Throwable $e) {
            // Ignore if table doesn't exist or other DB error
        }

        $admin_data = Admin::where('id', 1)->first();
        if ($admin_data && $admin_data->email) {
            $message_link = route('admin_message_detail', $message->id);
            $subject = 'New User Message';
            $email_message = 'Please click on the following link to see the new message from the user:<br><a href="'.$message_link.'">Click Here</a>';
            \Mail::to($admin_data->email)->send(new Websitemail($subject, $email_message));
        }

        // If AJAX request, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            $user = Auth::guard('web')->user();
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $obj->id,
                    'comment' => $obj->comment,
                    'type' => $obj->type,
                    'created_at' => $obj->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                    'sender_name' => $user->name ?? 'User'
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Message is sent successfully!');
    }

    /**
     * Poll for new messages (admin replies). Used for real-time chat updates.
     */
    public function message_poll(Request $request)
    {
        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();

        if (!$message) {
            return response()->json(['comments' => [], 'admin_typing' => false]);
        }

        $lastId = (int) $request->get('last_id', 0);
        $comments = MessageComment::where('message_id', $message->id)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        if ($comments->isNotEmpty()) {
            Message::where('id', $message->id)->update(['user_viewed_at' => now()]);
        }

        $admin_data = Admin::where('id', 1)->first();
        $admin_photo = ($admin_data && $admin_data->photo && $admin_data->photo != 'default.png')
            ? asset('uploads/' . $admin_data->photo)
            : asset('uploads/default.png');
        $admin_name = $admin_data->name ?? 'Admin';

        $result = $comments->map(function ($c) use ($admin_photo, $admin_name) {
            return [
                'id' => $c->id,
                'comment' => $c->comment,
                'type' => $c->type,
                'created_at' => $c->created_at->setTimezone('Asia/Manila')->format('M. j, Y h:i A'),
                'sender_photo' => $c->type === 'Admin' ? $admin_photo : null,
                'sender_name' => $c->type === 'Admin' ? $admin_name : null,
            ];
        });

        $adminTyping = \DB::table('typing_indicators')
            ->where('message_id', $message->id)
            ->where('typer', 'admin')
            ->where('updated_at', '>=', now()->subSeconds(6))
            ->exists();

        return response()->json([
            'comments' => $result->values()->all(),
            'admin_typing' => $adminTyping,
        ]);
    }

    /**
     * Return unread admin message count for notification badges (polled for real-time updates).
     */
    public function message_notification_count()
    {
        $count = 0;
        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();

        if ($message) {
            $query = MessageComment::where('message_id', $message->id)->where('type', 'Admin');
            if ($message->user_viewed_at) {
                $query->where('created_at', '>', $message->user_viewed_at);
            }
            $count = $query->count();
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Lightweight endpoint to check if admin is typing. Polled more frequently for responsive indicator.
     */
    public function typing_check(Request $request)
    {
        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();
        if (!$message) {
            return response()->json(['admin_typing' => false]);
        }
        $adminTyping = \DB::table('typing_indicators')
            ->where('message_id', $message->id)
            ->where('typer', 'admin')
            ->where('updated_at', '>=', now()->subSeconds(6))
            ->exists();
        return response()->json(['admin_typing' => $adminTyping]);
    }

    /**
     * Mark that the user is typing. Admin will see this via the poll.
     */
    public function typing_submit(Request $request)
    {
        $message = Message::where('user_id', Auth::guard('web')->user()->id)->first();
        if ($message) {
            \DB::table('typing_indicators')->updateOrInsert(
                ['message_id' => $message->id, 'typer' => 'user'],
                ['updated_at' => now()]
            );
        }
        return response()->json(['ok' => true]);
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function profile_submit(Request $request)
    {
        $user = User::where('id',Auth::guard('web')->user()->id)->first();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ]);

        if($request->photo) {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            if($user->photo != '') {
                unlink(public_path('uploads/'.$user->photo));
            }
            $final_name = 'user_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $user->photo = $final_name;
        }

        if($request->password != '') {
            $request->validate([
                'password' => 'required',
                'retype_password' => 'required|same:password',
            ]);
            $user->password = bcrypt($request->password);
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->address = $request->address;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->zip = $request->zip;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
