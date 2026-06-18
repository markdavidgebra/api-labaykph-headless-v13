<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageComment;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Booking;
use App\Models\Admin;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function users()
    {
        // Mark all users as viewed when admin visits the users page
        User::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);
        $users = User::get();
        return view('admin.user.users', compact('users'));
    }

    public function user_create()
    {
        return view('admin.user.user_create');
    }

    public function user_create_submit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'password' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $final_name = 'user_'.time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads'), $final_name);

        $obj = new User();
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->phone = $request->phone;
        $obj->country = $request->country;
        $obj->address = $request->address;
        $obj->state = $request->state;
        $obj->city = $request->city;
        $obj->zip = $request->zip;
        $obj->password = bcrypt($request->password);
        $obj->photo = $final_name;
        $obj->status = $request->status;
        $obj->save();

        return redirect()->route('admin_users')->with('success','User is Created Successfully');
    }

    public function user_edit($id)
    {
        $user = User::where('id',$id)->first();
        // Mark user as viewed when admin opens edit page
        if($user) {
            $user->admin_viewed_at = now();
            $user->save();
        }
        return view('admin.user.user_edit',compact('user'));
    }
    
    public function user_edit_submit(Request $request, $id)
    {
        $obj = User::where('id',$id)->first();
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ]);

        if($request->hasFile('photo'))
        {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if (!empty($obj->photo)) {
                $old_photo = public_path('uploads/'.$obj->photo);
                if (file_exists($old_photo) && is_file($old_photo)) {
                    unlink($old_photo);
                }
            }

            $final_name = 'user_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            $obj->photo = $final_name;
        }

        if($request->password != '')
        {
            $obj->password = bcrypt($request->password);
        }
        
        $obj->name = $request->name;
        $obj->email = $request->email;
        $obj->phone = $request->phone;
        $obj->country = $request->country;
        $obj->address = $request->address;
        $obj->state = $request->state;
        $obj->city = $request->city;
        $obj->zip = $request->zip;
        $obj->status = $request->status;
        $obj->save();

        return redirect()->route('admin_users')->with('success','User is Updated Successfully');
    }

    public function user_delete($id)
    {
        $total = Review::where('user_id',$id)->count();
        if($total > 0)
        {
            return redirect()->back()->with('error','User can not be deleted because it has some reviews');
        }

        $total1 = Message::where('user_id',$id)->count();
        if($total1 > 0) {
            return redirect()->back()->with('error','User can not be deleted because it has some messages');
        }

        $total2 = Wishlist::where('user_id',$id)->count();
        if($total2 > 0) {
            return redirect()->back()->with('error','User can not be deleted because it has some wishlist');
        }

        $total3 = Booking::where('user_id',$id)->count();
        if($total3 > 0) {
            return redirect()->back()->with('error','User can not be deleted because it has some bookings');
        }

        $obj = User::where('id',$id)->first();
        if (!empty($obj->photo)) {
            $photo_path = public_path('uploads/'.$obj->photo);
            if (file_exists($photo_path) && is_file($photo_path)) {
                unlink($photo_path);
            }
        }
        $obj->delete();

        return redirect()->route('admin_users')->with('success','User is Deleted Successfully');
    }

    /**
     * Force delete user and all related data (Super Admin only).
     * Deletes: messages (and comments), bookings, reviews, wishlist, then user.
     */
    public function user_force_delete($id)
    {
        if (!Auth::guard('superadmin')->check()) {
            abort(403, 'Only super admin can force delete users.');
        }

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin_users')->with('error', 'User not found.');
        }

        $messageIds = Message::where('user_id', $id)->pluck('id');

        // Delete typing indicators for user's messages
        if (\Schema::hasTable('typing_indicators') && $messageIds->isNotEmpty()) {
            \DB::table('typing_indicators')->whereIn('message_id', $messageIds)->delete();
        }

        // Delete message comments for user's messages
        MessageComment::whereIn('message_id', $messageIds)->delete();

        // Delete messages
        Message::where('user_id', $id)->delete();

        // Delete bookings
        Booking::where('user_id', $id)->delete();

        // Delete reviews
        Review::where('user_id', $id)->delete();

        // Delete wishlist
        Wishlist::where('user_id', $id)->delete();

        // Delete user photo if exists
        if (!empty($user->photo) && file_exists(public_path('uploads/' . $user->photo))) {
            unlink(public_path('uploads/' . $user->photo));
        }

        $user->delete();

        return redirect()->route('admin_users')->with('success', 'User and all related data have been permanently deleted.');
    }

    public function message()
    {
        $messages = Message::with('user')->get();
        return view('admin.user.message', compact('messages'));
    }

    public function message_detail($id)
    {
        // Mark message as viewed by admin (always update, not just when null)
        Message::where('id', $id)->update(['admin_viewed_at' => now()]);

        $message_comments = MessageComment::where('message_id',$id)->orderBy('id','desc')->get();
        return view('admin.user.message_detail', compact('message_comments','id'));
    }

    public function message_submit(Request $request,$id)
    {
        $obj = new MessageComment();
        $obj->message_id = $id;
        $obj->sender_id = 1;
        $obj->type = 'Admin';
        $obj->comment = $request->comment;
        $obj->save();

        \DB::table('typing_indicators')->where('message_id', $id)->where('typer', 'admin')->delete();

        // Update admin_viewed_at when admin replies (marking as seen/responded)
        Message::where('id', $id)->update(['admin_viewed_at' => now()]);

        $message_link = route('user_message');
        $subject = 'Admin Message';
        $message = 'Please click on the following link to see the new message from the admin:<br><a href="'.$message_link.'">Click Here</a>';

        $message_data = Message::with('user')->where('id',$id)->first();
        $user_email = $message_data->user->email;

        \Mail::to($user_email)->send(new Websitemail($subject,$message));

        // If AJAX request, return JSON response
        if ($request->ajax()) {
            $admin = Admin::where('id', 1)->first();
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $obj->id,
                    'comment' => $obj->comment,
                    'type' => $obj->type,
                    'created_at' => $obj->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                    'sender_name' => $admin->name ?? 'Admin'
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    /**
     * Clear all messages in a conversation (customer + admin).
     * Super admin only.
     */
    public function message_clear($id)
    {
        if (!Auth::guard('superadmin')->check()) {
            abort(403, 'Only super admin can clear messages.');
        }

        MessageComment::where('message_id', $id)->delete();

        try {
            if (\Schema::hasTable('typing_indicators')) {
                \DB::table('typing_indicators')->where('message_id', $id)->delete();
            }
        } catch (\Throwable $e) {
            // Ignore
        }

        return redirect()->route('admin_message_detail', $id)->with('success', 'All messages have been cleared.');
    }

    /**
     * Poll for new messages (customer replies). Used for real-time chat updates.
     */
    public function message_poll(Request $request, $id)
    {
        $lastId = (int) $request->get('last_id', 0);
        $comments = MessageComment::where('message_id', $id)
            ->where('id', '>', $lastId)
            ->where('type', 'User')
            ->orderBy('id', 'asc')
            ->get();

        if ($comments->isNotEmpty()) {
            Message::where('id', $id)->update(['admin_viewed_at' => now()]);
        }

        $result = $comments->map(function ($c) {
            $sender = User::where('id', $c->sender_id)->first();
            $sender_photo = ($sender && $sender->photo && $sender->photo != 'default.png')
                ? asset('uploads/' . $sender->photo)
                : asset('uploads/default.png');
            return [
                'id' => $c->id,
                'comment' => $c->comment,
                'type' => $c->type,
                'created_at' => $c->created_at->setTimezone('Asia/Manila')->format('M. j, Y h:i A'),
                'sender_photo' => $sender_photo,
                'sender_name' => $sender->name ?? 'User',
            ];
        });

        $userTyping = \DB::table('typing_indicators')
            ->where('message_id', $id)
            ->where('typer', 'user')
            ->where('updated_at', '>=', now()->subSeconds(6))
            ->exists();

        return response()->json([
            'comments' => $result->values()->all(),
            'user_typing' => $userTyping,
        ]);
    }

    /**
     * Mark that the admin is typing. User will see this via the poll.
     */
    public function typing_submit(Request $request, $id)
    {
        \DB::table('typing_indicators')->updateOrInsert(
            ['message_id' => $id, 'typer' => 'admin'],
            ['updated_at' => now()]
        );
        return response()->json(['ok' => true]);
    }

    /**
     * Find or create a message thread for a customer, then redirect to the message detail page.
     * Allows admin to initiate a conversation with a customer (e.g., from the booking page).
     */
    public function message_customer($user_id)
    {
        $message = Message::where('user_id', $user_id)->first();

        if (!$message) {
            $message = new Message();
            $message->user_id = $user_id;
            $message->save();
        }

        return redirect()->route('admin_message_detail', $message->id);
    }
}
