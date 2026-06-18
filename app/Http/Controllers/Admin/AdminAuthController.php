<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Mail\Websitemail;

class AdminAuthController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // Try superadmin first
        if (Auth::guard('superadmin')->attempt($data)) {
            return redirect()->route('admin_dashboard')->with('success', 'Login is successful!');
        }

        // Try admin
        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin_dashboard')->with('success', 'Login is successful!');
        }

        return redirect()->route('admin_login')->with('error', 'The information you entered is incorrect! Please try again!');
    }

    public function logout()
    {
        Auth::guard('superadmin')->logout();
        Auth::guard('admin')->logout();
        return redirect()->route('admin_login')->with('success', 'Logout is successful!');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function profile_submit(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $user = current_admin_user();
        if (!$user) {
            return redirect()->route('admin_login');
        }

        if ($request->photo) {
            $request->validate([
                'photo' => ['mimes:jpg,jpeg,png,gif', 'max:2024'],
            ]);
            $final_name = 'admin_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $final_name);
            if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
                unlink(public_path('uploads/'.$user->photo));
            }
            $user->photo = $final_name;
        }

        if ($request->password) {
            $request->validate([
                'password' => ['required'],
                'confirm_password' => ['required', 'same:password'],
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Profile is updated!');
    }

    public function forget_password()
    {
        return view('admin.forget-password');
    }

    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check superadmin first, then admin
        $superadmin = SuperAdmin::where('email', $request->email)->first();
        $admin = Admin::where('email', $request->email)->first();

        if (!$superadmin && !$admin) {
            return redirect()->back()->with('error', 'Email is not found!');
        }

        $token = hash('sha256', time());
        if ($superadmin) {
            $superadmin->token = $token;
            $superadmin->update();
        } else {
            $admin->token = $token;
            $admin->update();
        }

        $reset_link = url('admin/reset-password/'.$token.'/'.$request->email);
        $subject = "Password Reset";
        $message = "To reset password, please click on the link below:<br>";
        $message .= "<a href='".$reset_link."'>Click Here</a>";

        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'We have sent a password reset link to your email. Please check your email. If you do not find the email in your inbox, please check your spam folder.');
    }

    public function reset_password($token, $email)
    {
        $superadmin = SuperAdmin::where('email', $email)->where('token', $token)->first();
        $admin = Admin::where('email', $email)->where('token', $token)->first();

        if (!$superadmin && !$admin) {
            return redirect()->route('admin_login')->with('error', 'Token or email is not correct!');
        }
        return view('admin.reset-password', compact('token', 'email'));
    }

    public function reset_password_submit(Request $request, $token, $email)
    {
        $request->validate([
            'password' => ['required'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        $superadmin = SuperAdmin::where('email', $request->email)->where('token', $request->token)->first();
        $admin = Admin::where('email', $request->email)->where('token', $request->token)->first();

        if ($superadmin) {
            $superadmin->password = Hash::make($request->password);
            $superadmin->token = "";
            $superadmin->update();
        } elseif ($admin) {
            $admin->password = Hash::make($request->password);
            $admin->token = "";
            $admin->update();
        }

        return redirect()->route('admin_login')->with('success', 'Password reset is successful. You can login now.');
    }
}
