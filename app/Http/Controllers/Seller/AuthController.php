<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Seller;
use App\Mail\SellerRegistrationNotification;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 422);
            }
            return back()->with('error', 'Invalid credentials')->withInput();
        }

        // Check if user has seller account
        $seller = Seller::where('user_id', $user->id)->first();

        if (!$seller) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No seller account found. Please register as a seller.'
                ], 422);
            }
            return back()->with('error', 'No seller account found. Please register as a seller.')->withInput();
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 422);
            }
            return back()->with('error', 'Invalid credentials')->withInput();
        }

        // Check seller status
        if ($seller->isPending()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your application is under review. You will be notified once approved.'
                ], 422);
            }
            return back()->with('warning', 'Your application is under review. You will be notified once approved.')->withInput();
        }

        if ($seller->isRejected()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your application has been rejected. Please contact support.'
                ], 422);
            }
            return back()->with('error', 'Your application has been rejected. Please contact support.')->withInput();
        }

        if ($seller->isSuspended()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been suspended. Please contact support.'
                ], 422);
            }
            return back()->with('error', 'Your account has been suspended. Please contact support.')->withInput();
        }

        // Login seller
        $remember = $request->filled('remember');
        Auth::guard('seller')->login($seller, $remember);

        // If remember me is checked, extend session lifetime
        if ($remember) {
            // Set session to expire in 1 year (525600 minutes)
            config(['session.lifetime' => 525600]);
        }

        // Log activity
        $seller->logActivity('login', 'Seller logged in');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Welcome back, ' . $seller->business_name . '!',
                'redirect' => route('seller.dashboard')
            ]);
        }

        return redirect()->route('seller.dashboard')->with('success', 'Welcome back, ' . $seller->business_name . '!');
    }

    public function showRegister()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            // Account Information
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            
            // Business Information
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:individual,proprietorship,partnership,company',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'required|string|max:10',
            'business_address' => 'required|string',
            
            // Contact Information
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|size:10',
            
            // Bank Details
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'account_holder_name' => 'required|string|max:255',
            
            // Terms
            'terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'name' => $request->contact_person,
                'email' => $request->email,
                'mobile' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Create seller
            $seller = Seller::create([
                'user_id' => $user->id,
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'gst_number' => $request->gst_number,
                'pan_number' => $request->pan_number,
                'business_email' => $request->email,
                'business_phone' => $request->phone,
                'business_address' => $request->business_address,
                'business_city' => '', // Optional - can be extracted from address
                'business_state' => '', // Optional - can be extracted from address
                'business_pincode' => '', // Optional - can be extracted from address
                'status' => 'pending',
            ]);

            // Create bank details
            $seller->bankDetails()->create([
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'bank_name' => $request->bank_name,
                'branch_name' => '', // Optional
                'account_type' => 'savings', // Default
                'upi_id' => null,
            ]);

            DB::commit();

            // Send notification email to admin
            try {
                $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
                Mail::to($adminEmail)->send(new SellerRegistrationNotification($seller));
            } catch (\Exception $e) {
                \Log::error('Failed to send seller registration email: ' . $e->getMessage());
            }

            // Return JSON response for AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Your application is under review.',
                    'redirect' => route('seller.login')
                ]);
            }

            return redirect()->route('seller.login')->with('success', 'Registration successful! Your application is under review. You will be notified once approved.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Registration failed: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        if ($seller) {
            $seller->logActivity('logout', 'Seller logged out');
        }

        Auth::guard('seller')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('seller.login')->with('success', 'Logged out successfully');
    }

    public function showForgotPassword()
    {
        return view('seller.auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        // TODO: Implement forgot password
        return back()->with('info', 'Password reset functionality coming soon');
    }

    public function showResetPassword($token)
    {
        return view('seller.auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        // TODO: Implement reset password
        return redirect()->route('seller.login')->with('info', 'Password reset functionality coming soon');
    }
}
