<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\User;
use App\Support\MailContent;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
        ]);

        $token = hash('sha256', time());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'token' => $token,
            'status' => 0, // pending until email link is clicked
        ]);

        // Hardcoded public site — never APP_URL / api.labaykph.com.
        $verificationLink = 'https://labaykph.com/registration-verify/'.rawurlencode($request->email).'/'.$token;
        $subject = 'Verify your email — '.config('app.name');
        $message = MailContent::verification($request->name, $verificationLink);
        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please check your email to activate your account before logging in.',
        ]);
    }

    public function verifyRegistration(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $user = User::where('token', $data['token'])
            ->where('email', $data['email'])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'This verification link is invalid or has already been used.',
            ], 422);
        }

        $user->token = '';
        $user->status = 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Your email is verified. You can login now.',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The information you entered is incorrect.'],
            ]);
        }

        if ((int) $user->status !== 1) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email before logging in. Check your inbox for the activation link.'],
            ]);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->formatUser($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email is not found.'], 404);
        }

        $token = hash('sha256', time());
        $user->token = $token;
        $user->save();

        // Hardcoded public site — never APP_URL / api.labaykph.com.
        $resetLink = 'https://labaykph.com/reset-password/'.$token.'/'.rawurlencode($request->email);
        $subject = 'Reset your password — '.config('app.name');
        $message = MailContent::passwordReset($user->name ?? '', $resetLink);
        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return response()->json(['success' => true, 'message' => 'We have sent a password reset link to your email.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required',
            'retype_password' => 'required|same:password',
        ]);

        $user = User::where('email', $request->email)->where('token', $request->token)->first();
        if (!$user) {
            return response()->json(['message' => 'Token or email is not correct.'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->token = '';
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password reset is successful. You can login now.']);
    }

    private function formatUser(User $user): array
    {
        return with_upload_urls($user, ['photo']);
    }
}
