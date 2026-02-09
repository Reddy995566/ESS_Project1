<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        
        // Get seller settings
        $settingsData = $seller->settings()->pluck('value', 'key')->toArray();
        
        $settings = [
            // Simple Shiprocket settings - just API credentials
            'shiprocket_email' => $settingsData['shiprocket_email'] ?? '',
            'shiprocket_password' => $settingsData['shiprocket_password'] ?? '',
            'shiprocket_enabled' => ($settingsData['shiprocket_enabled'] ?? '0') === '1',
            
            // Email notification settings only
            'email_new_order' => ($settingsData['email_new_order'] ?? '1') === '1',
            'email_product_approved' => ($settingsData['email_product_approved'] ?? '1') === '1',
            'email_product_rejected' => ($settingsData['email_product_rejected'] ?? '1') === '1',
            'email_payout_processed' => ($settingsData['email_payout_processed'] ?? '1') === '1',
            'email_low_stock' => ($settingsData['email_low_stock'] ?? '1') === '1',
            'email_new_review' => ($settingsData['email_new_review'] ?? '1') === '1',
        ];
        
        return view('seller.settings.index', compact('seller', 'user', 'bankDetails', 'settings'));
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
        
        // Store only email notification preferences in seller_settings table
        $preferences = [
            'email_new_order' => $request->boolean('email_new_order'),
            'email_product_approved' => $request->boolean('email_product_approved'),
            'email_product_rejected' => $request->boolean('email_product_rejected'),
            'email_payout_processed' => $request->boolean('email_payout_processed'),
            'email_low_stock' => $request->boolean('email_low_stock'),
            'email_new_review' => $request->boolean('email_new_review'),
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
        $seller = Auth::guard('seller')->user();
        
        if ($seller->two_factor_enabled) {
            return response()->json([
                'success' => false,
                'message' => '2FA is already enabled for your account',
            ]);
        }
        
        // Generate secret key
        $secret = $this->generateSecretKey();
        
        // Generate QR code URL
        $qrCodeUrl = $this->generateQRCodeUrl($seller, $secret);
        
        // Store secret temporarily (not confirmed yet)
        $seller->update([
            'two_factor_secret' => encrypt($secret)
        ]);
        
        return response()->json([
            'success' => true,
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'message' => 'Scan the QR code with your authenticator app and enter the code to confirm',
        ]);
    }
    
    public function confirm2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);
        
        $seller = Auth::guard('seller')->user();
        
        if (!$seller->two_factor_secret) {
            return response()->json([
                'success' => false,
                'message' => 'No 2FA setup in progress',
            ], 400);
        }
        
        $secret = decrypt($seller->two_factor_secret);
        
        // Verify the code
        if (!$this->verifyCode($secret, $request->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code',
            ], 400);
        }
        
        // Generate recovery codes
        $recoveryCodes = $this->generateRecoveryCodes();
        
        // Enable 2FA
        $seller->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $recoveryCodes,
        ]);
        
        // Log activity
        $seller->logActivity('2fa_enabled', 'Two-factor authentication enabled');
        
        return response()->json([
            'success' => true,
            'recovery_codes' => $recoveryCodes,
            'message' => '2FA enabled successfully! Save these recovery codes in a safe place.',
        ]);
    }
    
    public function disable2FA(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        
        $seller = Auth::guard('seller')->user();
        
        // Verify password
        if (!Hash::check($request->password, $seller->user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid password',
            ], 400);
        }
        
        // Disable 2FA
        $seller->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);
        
        // Log activity
        $seller->logActivity('2fa_disabled', 'Two-factor authentication disabled');
        
        return response()->json([
            'success' => true,
            'message' => '2FA disabled successfully',
        ]);
    }
    
    private function generateSecretKey()
    {
        return base32_encode(random_bytes(20));
    }
    
    private function generateQRCodeUrl($seller, $secret)
    {
        $appName = config('app.name');
        $email = $seller->user->email;
        
        $otpAuthUrl = "otpauth://totp/{$appName}:{$email}?secret={$secret}&issuer={$appName}";
        
        return "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($otpAuthUrl);
    }
    
    private function verifyCode($secret, $code)
    {
        // Simple TOTP verification (you might want to use a library like pragmarx/google2fa)
        $timeSlice = floor(time() / 30);
        
        for ($i = -1; $i <= 1; $i++) {
            $calculatedCode = $this->generateTOTP($secret, $timeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function generateTOTP($secret, $timeSlice)
    {
        $key = base32_decode($secret);
        $time = pack('N*', 0) . pack('N*', $timeSlice);
        $hash = hash_hmac('sha1', $time, $key, true);
        $offset = ord($hash[19]) & 0xf;
        $code = (
            ((ord($hash[$offset + 0]) & 0x7f) << 24) |
            ((ord($hash[$offset + 1]) & 0xff) << 16) |
            ((ord($hash[$offset + 2]) & 0xff) << 8) |
            (ord($hash[$offset + 3]) & 0xff)
        ) % 1000000;
        
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }
    
    private function generateRecoveryCodes()
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(Str::random(4) . '-' . Str::random(4));
        }
        return $codes;
    }
    
    public function updateShiprocket(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        
        $request->validate([
            'shiprocket_email' => 'required|email',
            'shiprocket_password' => 'required|string',
        ]);
        
        try {
            $settings = [
                'shiprocket_email' => $request->shiprocket_email,
                'shiprocket_password' => $request->shiprocket_password,
                'shiprocket_enabled' => $request->boolean('shiprocket_enabled') ? '1' : '0',
            ];
            
            foreach ($settings as $key => $value) {
                $seller->settings()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
            
            // Log activity
            $seller->logActivity('shiprocket_updated', 'Updated Shiprocket API credentials');
            
            return response()->json([
                'success' => true,
                'message' => 'Shiprocket API credentials updated successfully!',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating Shiprocket settings: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    public function testShiprocket(Request $request)
    {
        $request->validate([
            'shiprocket_email' => 'required|email',
            'shiprocket_password' => 'required|string',
        ]);
        
        try {
            // Test Shiprocket connection
            $shiprocketService = app(\App\Services\ShiprocketService::class);
            $token = $shiprocketService->authenticate($request->shiprocket_email, $request->shiprocket_password);
            
            if ($token) {
                return response()->json([
                    'success' => true,
                    'message' => 'Shiprocket connection successful!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Shiprocket credentials',
                ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

// Helper functions for base32 encoding/decoding
if (!function_exists('base32_encode')) {
    function base32_encode($data) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $v = 0;
        $vbits = 0;
        
        for ($i = 0, $j = strlen($data); $i < $j; $i++) {
            $v <<= 8;
            $v += ord($data[$i]);
            $vbits += 8;
            
            while ($vbits >= 5) {
                $vbits -= 5;
                $output .= $alphabet[$v >> $vbits];
                $v &= ((1 << $vbits) - 1);
            }
        }
        
        if ($vbits > 0) {
            $v <<= (5 - $vbits);
            $output .= $alphabet[$v];
        }
        
        return $output;
    }
}

if (!function_exists('base32_decode')) {
    function base32_decode($data) {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $output = '';
        $v = 0;
        $vbits = 0;
        
        for ($i = 0, $j = strlen($data); $i < $j; $i++) {
            $v <<= 5;
            if (($x = strpos($alphabet, $data[$i])) !== false) {
                $v += $x;
                $vbits += 5;
                if ($vbits >= 8) {
                    $vbits -= 8;
                    $output .= chr($v >> $vbits);
                    $v &= ((1 << $vbits) - 1);
                }
            }
        }
        
        return $output;
    }
}