<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Website\CartController;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('url.intended') && str_contains(session('url.intended'), 'checkout')) {
            session()->flash('error', 'Please login to place your order');
        }
        return view('website.auth.login');
    }

    public function showRegisterForm()
    {
        return view('website.auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'mobile' => 'required|digits:10|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            Auth::login($user);

            // Sync Guest Cart to DB
            CartController::syncUserCart($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'redirect' => route('home')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                // Sync Guest Cart to DB
                CartController::syncUserCart($user);

                // Update last login
                Auth::user()->update(['last_login_at' => now()]);

                // Check for intended URL
                $redirect = session('url.intended', route('home'));
                session()->forget('url.intended');

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirect' => $redirect
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully');
    }

    public function checkAuth()
    {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user() : null
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
            ];

            // If changing password
            if ($request->filled('current_password')) {
                $rules['current_password'] = 'required';
                $rules['new_password'] = 'required|min:6|confirmed';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify current password if changing
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['current_password' => ['Current password is incorrect']]
                    ], 422);
                }

                $user->password = Hash::make($request->new_password);
            }

            // Update profile
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
}
