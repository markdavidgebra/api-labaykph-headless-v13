<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Mail\Websitemail;

class AdminSubscriberController extends Controller
{
    public function subscribers()
    {
        // Mark all subscribers as viewed when admin visits the page
        Subscriber::whereNull('admin_viewed_at')->update(['admin_viewed_at' => now()]);
        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();
        return view('admin.subscriber.index', compact('subscribers'));
    }

    public function send_email()
    {
        return view('admin.subscriber.send_email');
    }

    public function send_email_submit(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $subject = $request->subject;
        $message = $request->message;

        $all_subs = Subscriber::where('status','Active')->get();
        $total_subscribers = $all_subs->count();
        $success_count = 0;
        $failed_count = 0;
        $failed_emails = [];

        foreach($all_subs as $index => $item)
        {
            try {
                \Mail::to($item->email)->send(new Websitemail($subject,$message));
                $success_count++;
                
                // Add delay between emails to avoid rate limiting (1 second delay)
                if($index < $total_subscribers - 1) {
                    sleep(1);
                }
            } catch (\Exception $e) {
                $failed_count++;
                $failed_emails[] = $item->email;
                
                // Log the error for debugging but don't show technical details to user
                \Log::error('Email sending failed for subscriber: ' . $item->email, [
                    'error' => $e->getMessage()
                ]);
                
                // Check for rate limiting errors (common patterns)
                $error_message = strtolower($e->getMessage());
                $is_rate_limit = strpos($error_message, 'too many emails') !== false || 
                                strpos($error_message, 'rate limit') !== false ||
                                strpos($error_message, '550') !== false ||
                                strpos($error_message, 'quota') !== false ||
                                strpos($error_message, 'exceeded') !== false;
                
                // If it's a rate limit error, stop sending to avoid more failures
                if($is_rate_limit) {
                    break;
                }
            }
        }

        // Return appropriate message based on results
        if($success_count > 0 && $failed_count == 0) {
            return redirect()->back()->with('success', "Email sent successfully to {$success_count} subscriber(s).");
        } elseif($success_count > 0 && $failed_count > 0) {
            return redirect()->back()->with('warning', "Email sent to {$success_count} subscriber(s), but {$failed_count} failed to send. Please try again later or check your email service configuration.");
        } else {
            return redirect()->back()->with('error', 'Failed to send emails. Please check your email service configuration or try again later. If you have many subscribers, consider sending in smaller batches.');
        }
    }

    public function subscriber_approve($id)
    {
        $obj = Subscriber::where('id', $id)->first();
        if ($obj) {
            $obj->token = '';
            $obj->status = 'Active';
            $obj->save();
            return redirect()->back()->with('success', 'Subscriber approved successfully.');
        }
        return redirect()->back()->with('error', 'Subscriber not found.');
    }

    public function subscriber_delete($id)
    {
        $obj = Subscriber::where('id',$id)->first();
        $obj->delete();
        return redirect()->back()->with('success', 'Subscriber is deleted successfully');
    }
}
