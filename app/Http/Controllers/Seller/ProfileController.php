<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\ImageKitService;

class ProfileController extends Controller
{
    protected $imageKitService;

    public function __construct(ImageKitService $imageKitService)
    {
        $this->imageKitService = $imageKitService;
    }

    public function index()
    {
        $seller = Auth::guard('seller')->user();
        $user = $seller->user;
        
        // Get login history
        $loginHistory = $seller->activityLogs()
            ->where('action', 'login')
            ->latest()
            ->limit(10)
            ->get();
        
        return view('seller.profile.index', compact('seller', 'user', 'loginHistory'));
    }
    
    public function update(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $user = $seller->user;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:10|unique:users,mobile,' . $user->id,
        ]);
        
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ]);
            
            // Log activity
            $seller->logActivity('profile_updated', 'Updated profile information');
            
            return back()->with('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
    
    public function updatePassword(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $user = $seller->user;
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        
        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }
        
        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            
            // Log activity
            $seller->logActivity('password_changed', 'Changed password');
            
            return back()->with('success', 'Password changed successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error changing password: ' . $e->getMessage());
        }
    }
    
    public function uploadAvatar(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $user = $seller->user;
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            $file = $request->file('avatar');
            $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Upload to ImageKit
            $result = $this->imageKitService->upload($file, $fileName, 'sellers/avatars');
            
            $user->update([
                'avatar' => $result['url'],
            ]);
            
            // Log activity
            $seller->logActivity('avatar_updated', 'Updated profile picture');
            
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'message' => 'Profile picture updated successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading avatar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
