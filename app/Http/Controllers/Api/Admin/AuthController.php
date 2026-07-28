<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Support\AdminAccess;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $superAdmin = SuperAdmin::where('email', $request->email)->first();
        if ($superAdmin && Hash::check($request->password, $superAdmin->password)) {
            $token = $superAdmin->createToken('admin-token', ['superadmin'])->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $this->formatAdmin($superAdmin),
            ]);
        }

        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $role = AdminAccess::role($admin);
            $token = $admin->createToken('admin-token', [$role])->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $this->formatAdmin($admin),
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The information you entered is incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true]);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $this->formatAdmin($request->user())]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('photo')) {
            $request->validate(['photo' => 'mimes:jpg,jpeg,png,gif|max:2024']);
            $finalName = 'admin_'.time().'.'.$request->photo->extension();
            $request->photo->move(public_path('uploads'), $finalName);
            if ($user->photo && file_exists(public_path('uploads/'.$user->photo))) {
                unlink(public_path('uploads/'.$user->photo));
            }
            $user->photo = $finalName;
        }

        if ($request->password) {
            $request->validate(['password' => 'required', 'confirm_password' => 'same:password']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['success' => true, 'user' => $this->formatAdmin($user)]);
    }

    private function formatAdmin($user): array
    {
        $data = with_upload_urls($user, ['photo']);
        $data['role'] = AdminAccess::role($user);

        return $data;
    }
}
