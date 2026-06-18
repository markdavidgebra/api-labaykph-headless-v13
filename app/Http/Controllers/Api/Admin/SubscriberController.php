<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function activeCount()
    {
        return response()->json([
            'count' => Subscriber::where('status', 'Active')->count(),
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subscribers = Subscriber::where('status', 'Active')->get();
        $total = $subscribers->count();

        if ($total === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscribers found.',
                'success_count' => 0,
                'failed_count' => 0,
            ], 422);
        }

        $subject = $request->subject;
        $message = $request->message;
        $successCount = 0;
        $failedCount = 0;

        foreach ($subscribers as $index => $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new Websitemail($subject, $message));
                $successCount++;

                if ($index < $total - 1) {
                    sleep(1);
                }
            } catch (\Throwable $e) {
                $failedCount++;

                Log::error('Email sending failed for subscriber: '.$subscriber->email, [
                    'error' => $e->getMessage(),
                ]);

                $errorMessage = strtolower($e->getMessage());
                $isRateLimit = str_contains($errorMessage, 'too many emails')
                    || str_contains($errorMessage, 'rate limit')
                    || str_contains($errorMessage, '550')
                    || str_contains($errorMessage, 'quota')
                    || str_contains($errorMessage, 'exceeded');

                if ($isRateLimit) {
                    break;
                }
            }
        }

        if ($successCount > 0 && $failedCount === 0) {
            return response()->json([
                'success' => true,
                'message' => "Email sent successfully to {$successCount} subscriber(s).",
                'success_count' => $successCount,
                'failed_count' => $failedCount,
            ]);
        }

        if ($successCount > 0 && $failedCount > 0) {
            return response()->json([
                'success' => true,
                'warning' => true,
                'message' => "Email sent to {$successCount} subscriber(s), but {$failedCount} failed to send. Please try again later or check your email service configuration.",
                'success_count' => $successCount,
                'failed_count' => $failedCount,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send emails. Please check your email service configuration or try again later. If you have many subscribers, consider sending in smaller batches.',
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ], 422);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|distinct',
        ]);

        $deleted = Subscriber::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} subscriber(s) deleted successfully.",
            'deleted' => $deleted,
        ]);
    }
}
