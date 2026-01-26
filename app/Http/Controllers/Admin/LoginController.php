<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find admin by email
        $admin = Admin::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        // Check if admin exists and password matches
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Update last login
            $admin->update(['last_login_at' => now()]);

            // Log successful login
            LoginHistory::create([
                'admin_id' => $admin->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
                'login_at' => now(),
            ]);

            // Login using Laravel Auth guard with remember me (stays logged in for 5 years)
            Auth::guard('admin')->login($admin, true);

            // Set session with long lifetime
            session([
                'admin_authenticated' => true,
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'admin_name' => $admin->name,
                'admin_role' => $admin->role ? $admin->role->name : 'Admin',
            ]);

            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Log failed login attempt
        LoginHistory::create([
            'admin_id' => $admin ? $admin->id : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'failed',
            'login_at' => now(),
        ]);

        return back()->withErrors([
            'email' => 'Invalid credentials. Please try again.',
        ])->withInput($request->only('email'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        session()->forget('admin_authenticated');
        session()->forget('admin_email');
        session()->forget('admin_id');
        session()->forget('admin_name');
        return redirect()->route('admin.login.form');
    }
}