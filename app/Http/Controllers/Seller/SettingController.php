<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\ImageKitService;

class SettingController extends Controller
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
        $bankDetails = $seller->bankDetails;
        
        return view('seller.settings.index', compact('seller', 'user', 'bankDetails'));
    }
    
    public function updateProfile(Request $request)
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
            
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating profile: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function updateBusiness(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:individual,company,partnership',
            'business_email' => 'required|email',
            'business_phone' => 'required|string|max:10',
            'business_address' => 'required|string',
            'business_city' => 'required|string|max:255',
            'business_state' => 'required|string|max:255',
            'business_pincode' => 'required|string|max:6',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'required|string|max:10',
            'description' => 'nullable|string',
        ]);
        
        try {
            $seller->update([
                'business_name' => $request->business_name,
                'business_type' => $request->business_type,
                'business_email' => $request->business_email,
                'business_phone' => $request->business_phone,
                'business_address' => $request->business_address,
                'business_city' => $request->business_city,
                'business_state' => $request->business_state,
                'business_pincode' => $request->business_pincode,
                'gst_number' => $request->gst_number,
                'pan_number' => $request->pan_number,
                'description' => $request->description,
            ]);
            
            // Log activity
            $seller->logActivity('business_updated', 'Updated business information');
            
            return response()->json([
                'success' => true,
                'message' => 'Business details updated successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating business details: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function updateBank(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'bank_name' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
            'account_type' => 'required|in:savings,current',
            'upi_id' => 'nullable|string|max:255',
        ]);
        
        try {
            $bankDetails = $seller->bankDetails;
            
            if ($bankDetails) {
                $bankDetails->update([
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_type' => $request->account_type,
                    'upi_id' => $request->upi_id,
                    'is_verified' => false, // Reset verification
                ]);
            } else {
                $seller->bankDetails()->create([
                    'account_holder_name' => $request->account_holder_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'account_type' => $request->account_type,
                    'upi_id' => $request->upi_id,
                ]);
            }
            
            // Log activity
            $seller->logActivity('bank_updated', 'Updated bank details');
            
            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully! Verification required.',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating bank details: ' . $e->getMessage(),
            ], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }
        
        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            
            // Log activity
            $seller->logActivity('password_changed', 'Changed password');
            
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error changing password: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function uploadLogo(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            $file = $request->file('logo');
            $fileName = 'seller_logo_' . $seller->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Upload to ImageKit
            $result = $this->imageKitService->upload($file, $fileName, 'sellers/logos');
            
            $seller->update([
                'logo' => $result['url'],
            ]);
            
            // Log activity
            $seller->logActivity('logo_updated', 'Updated business logo');
            
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'message' => 'Logo uploaded successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading logo: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function uploadBanner(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            $file = $request->file('banner');
            $fileName = 'seller_banner_' . $seller->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Upload to ImageKit
            $result = $this->imageKitService->upload($file, $fileName, 'sellers/banners');
            
            $seller->update([
                'banner' => $result['url'],
            ]);
            
            // Log activity
            $seller->logActivity('banner_updated', 'Updated business banner');
            
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'message' => 'Banner uploaded successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading banner: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function updateNotifications(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        // Store notification preferences in seller_settings table
        $preferences = [
            'email_new_order' => $request->boolean('email_new_order'),
            'email_product_approved' => $request->boolean('email_product_approved'),
            'email_product_rejected' => $request->boolean('email_product_rejected'),
            'email_payout_processed' => $request->boolean('email_payout_processed'),
            'email_low_stock' => $request->boolean('email_low_stock'),
            'email_new_review' => $request->boolean('email_new_review'),
            'sms_new_order' => $request->boolean('sms_new_order'),
            'sms_payout_processed' => $request->boolean('sms_payout_processed'),
        ];
        
        try {
            foreach ($preferences as $key => $value) {
                $seller->settings()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value ? '1' : '0']
                );
            }
            
            // Log activity
            $seller->logActivity('notifications_updated', 'Updated notification preferences');
            
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating preferences: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function enable2FA(Request $request)
    {
        // TODO: Implement 2FA
        return response()->json([
            'success' => false,
            'message' => '2FA functionality coming soon',
        ]);
    }
    
    public function disable2FA(Request $request)
    {
        // TODO: Implement 2FA
        return response()->json([
            'success' => false,
            'message' => '2FA functionality coming soon',
        ]);
    }
}
